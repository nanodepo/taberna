<?php

use App\Domains\User\Models\User;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Actions\CreateOrderAction;
use NanoDepo\Taberna\Data\OrderData;
use NanoDepo\Taberna\Data\OrderItemData;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

new
#[Layout('layouts.shop')]
class extends Component {
    use WithModal;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'email'])]
    public string $email = '';
    #[Validate(['required', 'string', 'max:250'])]
    public string $address = '';
    #[Validate(['required', 'string', 'max:36'])]
    public string $index = '';
    #[Validate(['required', 'string', 'max:4'])]
    public ?string $payment_method = null;
    #[Validate(['nullable', 'string', 'max:1000'])]
    public string $comment = '';

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    public function order(): void
    {
        $this->open();
    }

    public function submit(): void
    {
        $this->validate();
        $user = auth()->check() ? auth()->user() : $this->register();
        $order = $this->createOrder($user);
        session()->remove('basket');
        alert()->primary('Your order has been created');
        $this->redirectRoute('profile.order.show', $order->id);
    }

    private function register(): User
    {
        $user = User::firstOrCreate([
            'email' => $this->email,
        ], [
            'name' => $this->name,
            'password' => Hash::make(\Illuminate\Support\Str::random()),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    private function createOrder(User $user): Order
    {
        $dto = new OrderData(
            user: $user,
            items: collect(session()->get('basket'))->map(function ($item) {
                return new OrderItemData(
                    product: Product::find($item[0]['product']),
                    quantity: intval($item[0]['quantity']),
                    variant: Variant::find($item[0]['variant']),
                    addons: isset($item[0]['addons'])
                        ? Addon::query()->whereIn('id', $item[0]['addons'])->get()
                        : null,
                );
            }),
            shipping_address: $this->address . ' | ' . $this->index,
            payment_method: $this->payment_method,
            comment: $this->comment
        );

        return (new CreateOrderAction)->handle($dto);
    }

    public function with(): array
    {
        $items = collect(session()->get('basket'))->map(function ($item) {
            $product = Product::find($item[0]['product']);
            if (is_null($product)) {
                return null;
            }

            $variant = Variant::find($item[0]['variant'])?->load('options.attribute');
            $addons = isset($item[0]['addons']) && is_iterable($item[0]['addons'])
                ? Addon::query()->select(['id', 'name', 'price'])->whereIn('id', $item[0]['addons'])->get()
                : null;

            $price = is_null($variant) ? ($product->price - $product->discount) : ($variant->price - ($variant->discount ?? $product->discount));

            $price = $price * intval($item[0]['quantity']);

            if ($addons) {
                $price = $price + $addons->sum('price');
            }

            return literal(
                id: $item[0]['id'],
                product: $product,
                variant: $variant,
                addons: $addons,
                quantity: intval($item[0]['quantity']),
                price: $price
            );
        })->filter(fn($item) => !is_null($item));

        // dd($items);

        return [
            'items' => $items,
        ];
    }
} ?>

<x-ui::layout.single x-data="{ policy: false }">

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::header title="Basket" subtitle="You are one step away from happiness" class="my-12" />

            <x-taberna::menubar />

        </x-ui::section>

        <x-ui::section>
            @if($items->isNotEmpty())
                <x-ui::title
                    title="Selected Products"
                    subtitle="Products and add-ons you have selected"
                    class="mb-3"
                />

                <x-ui::list>
                    @foreach($items as $item)
                        <x-ui::list.item
                            :subhead="is_null($item->variant) ? price($item->product->price * $item->quantity)->formatted() : price($item->variant->price * $item->quantity)->formatted()"
                            :title="is_null($item->variant) ? $item->product->name : $item->product->name .' (' . $item->variant->options->pluck('name')->join('/').')'"
                            subtitle="Quantity: {{ $item->quantity }} | Discount: {{ price($item->variant->discount ?? $item->product->discount)->formatted() }}"
                        >
                            <x-slot name="before">
                                <div class="w-14 h-14 bg-surface bg-cover bg-center rounded" style="background-image: url('{{ (is_null($item->variant) && $item->variant?->image) ? $item->variant->image?->thumbnail(96) : $item->product->image?->thumbnail(96) }}')"></div>
                            </x-slot>

                            <x-slot name="after">
                                <x-ui::circle icon="trash" wire:click="delete('{{ $item->id }}')" color="destructive" />
                            </x-slot>
                        </x-ui::list.item>

                        @if(!is_null($item->addons))
                            @foreach($item->addons as $addon)
                                <x-ui::list.value
                                    icon="minus"
                                    :title="$addon->name"
                                    :subtitle="$addon->description"
                                    accent
                                >
                                    {{ price($addon->price)->formatted() }}
                                </x-ui::list.value>
                            @endforeach
                        @endif
                    @endforeach
                </x-ui::list>

                <div class="flex flex-row justify-between mt-6">
                    <x-ui::title
                        title="To be paid"
                        subtitle="The amount is indicated taking into account the discount"
                    />

                    <div class="flex-none text-2xl text-accent font-medium">
                        {{ price($items->sum('price'))->formatted() }}
                    </div>
                </div>

                <x-taberna::main-button type="button" wire:click="order" class="mt-6">
                    Place an order
                </x-taberna::main-button>
            @else
                <x-ui::empty text="Your cart is empty, please add items to it first" />
            @endif
        </x-ui::section>

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title
                    title="Place an order"
                    subtitle="Fill in all the fields and we will start preparing your order"
                />

                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <form id="order-form" wire:submit="submit" class="flex flex-col gap-3 py-3">
                    <x-ui::field wire:model="name" label="Name" hint="Specify the name of the recipient of the goods" max="64">
                        <x-ui::input
                            x-model="field"
{{--                            placeholder="John Dou"--}}
                            maxlength="64"
                            max="64"
                            required
                        />
                    </x-ui::field>

                    <x-ui::field wire:model="email" label="Email" hint="No spam or promotional emails, just for communication" max="64">
                        <x-ui::input
                            type="email"
                            x-model="field"
{{--                            placeholder="example@gmail.com"--}}
                            maxlength="64"
                            max="64"
                            required
                        />
                    </x-ui::field>

                    <x-ui::divider />

                    <x-ui::title
                        title="Delivery address"
                        subtitle="Region, settlement and post office"
                    />

                    <x-ui::field wire:model="address" label="Address" hint="Please indicate city and region, if applicable" max="250">
                        <x-ui::input.textarea
                            x-model="field"
{{--                            placeholder="Lubny, Poltava region"--}}
                            rows="2"
                            maxlength="250"
                            max="250"
                            required
                        />
                    </x-ui::field>

                    <x-ui::field wire:model="index" label="Department" hint="New post office or postcode" max="36">
                        <x-ui::input
                            type="number"
                            x-model="field"
{{--                            placeholder="5th Department"--}}
                            maxlength="36"
                            required
                        />
                    </x-ui::field>

                    <x-ui::divider />

                    <x-ui::title
                        title="Payment method"
                        subtitle="At your convenience?"
                    />

                    <x-ui::list>
                        <x-ui::list.radio
                            wire:model.live="payment_method"
                            title="Сash"
                            subtitle="At the post office after receiving the goods"
                            value="cash"
                        />

                        <x-ui::list.radio
                            wire:model.live="payment_method"
                            title="Transfer to card"
                            subtitle="After the manager's call"
                            value="card"
                        />
                    </x-ui::list>

                    <x-ui::divider />

                    <x-ui::field wire:model="comment" label="Comment on the order">
                        <x-ui::input.textarea
                            x-model="field"
{{--                            placeholder="Can you paint the bed in two colors: the base is white and the slats are pink?"--}}
                            rows="2"
                            maxlength="1000"
                            max="1000"
                        />
                    </x-ui::field>

                    <x-ui::divider />

                    <div class="flex flex-row gap-6 items-center">
                        <x-ui::input.checkbox x-model="policy" />
                        <div class="">
                            I agree with
                            <a href="{{ route('privacy-policy') }}" class="text-accent link">the terms of personal data processing</a>
                            and accept them.
                        </div>
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" variant="text" color="secondary">
                    Cancel
                </x-ui::button>

                <x-ui::button before="rocket-launch" form="order-form" type="submit" x-bind:disabled="!policy">
                    Order
                </x-ui::button>
            </x-slot>
        </x-ui::dialog>

    </x-slot>

</x-ui::layout.single>
