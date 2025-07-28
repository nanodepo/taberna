<?php

use App\Domains\User\Models\User;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Actions\CreateOrderAction;
use NanoDepo\Taberna\Data\OrderData;
use NanoDepo\Taberna\Data\OrderItemData;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use HasAlert;

    public bool $isNotEmpty = false;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'email'])]
    public string $email = '';
    #[Validate(['required', 'numeric'])]
    public ?int $phone = null;
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
            $this->phone = auth()->user()->phone;
        }

        $this->init();
    }

    #[On('basket-item-deleted')]
    public function init(): void
    {
        if (session()->has('basket') && collect(session()->get('basket'))->isNotEmpty()) {
            $this->isNotEmpty = true;
        }
    }

    public function updatedPaymentMethod(): void
    {
        $this->dispatch('paymentselected');
    }

    public function submit(): void
    {
        $this->validate();
        $user = auth()->check() ? auth()->user() : $this->register();
        $order = $this->createOrder($user);
        session()->remove('basket');
        alert()->primary('Ваше замовлення створено');
        $this->redirectRoute('thank-you', $order->id);
    }

    private function register(): User
    {
        $user = User::firstOrCreate([
            'email' => $this->email,
        ], [
            'name' => $this->name,
            'phone' => $this->phone,
            'password' => Hash::make(\Illuminate\Support\Str::random()),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    private function createOrder(User $user): Order
    {
        return (new CreateOrderAction)->handle(new OrderData(
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
        ));
    }
} ?>

<x-ui::section>
    @if($isNotEmpty)
        <form x-data="{ policy: false }" wire:submit="submit" class="flex flex-col gap-3">
            <x-ui::title
                title="Особисті дані"
                subtitle="Вказуйте дані одержувача"
            />

            <x-ui::field wire:model="name" label="Ім'я та прізвище" hint="Вказуйте ім'я одержувача товару" max="64">
                <x-ui::input
                    x-model="field"
                    placeholder="Іванов Іван Іванович"
                    maxlength="64"
                    max="64"
                    required
                />
            </x-ui::field>

            <x-ui::field wire:model="email" label="Email" hint="Жодного спаму та рекламних листів, тільки для зв'язку" max="64">
                <x-ui::input
                    type="email"
                    x-model="field"
                    placeholder="example@gmail.com"
                    maxlength="64"
                    max="64"
                    required
                />
            </x-ui::field>

            <x-ui::field wire:model="phone" label="Телефон" hint="Для зв'язку з менеджером" max="12">
                <x-ui::input
                    type="number"
                    x-model="field"
                    placeholder="380987654321"
                    maxlength="12"
                    required
                />
            </x-ui::field>

            <x-ui::divider />

            <x-ui::title
                title="Адреса доставки"
                subtitle="Область, населений пункт та відділення пошти"
            />

            <x-ui::field wire:model="address" label="Адреса" hint="Вкажіть місто та область, якщо це актуально" max="250">
                <x-ui::input.textarea
                    x-model="field"
                    placeholder="Лубни, Полтавська область"
                    rows="2"
                    maxlength="250"
                    max="250"
                    required
                />
            </x-ui::field>

            <x-ui::field wire:model="index" label="Відділення" hint="Відділення нової пошти чи індекс" max="36">
                <x-ui::input
                    type="number"
                    x-model="field"
                    placeholder="5-те Відділення"
                    maxlength="36"
                    required
                />
            </x-ui::field>

            <x-ui::divider />

            <x-ui::title
                title="Метод оплати"
                subtitle="Як вам буде зручно?"
            />

            <x-ui::list>
                <x-ui::list.radio
                    wire:model.live="payment_method"
                    title="Післяплатою"
                    subtitle="На пошті після отримання товару"
                    value="post"
                />

                <x-ui::list.radio
                    wire:model.live="payment_method"
                    title="Переказ на картку"
                    subtitle="Після дзвінка менеджера"
                    value="card"
                />
            </x-ui::list>

            <x-ui::divider />

            <x-ui::field wire:model="comment" label="Коментар до замовлення">
                <x-ui::input.textarea
                    x-model="field"
                    placeholder="Чи можете ви пофарбувати ліжко у два кольори: основа біла та рожеві билиця?"
                    rows="2"
                    maxlength="1000"
                    max="1000"
                />
            </x-ui::field>

            <x-ui::divider />

            <div class="flex flex-row gap-3 items-center">
                <x-ui::input.checkbox x-model="policy" />
                <div class="">Я згоден з
                    <a href="{{ route('privacy-policy') }}" class="text-accent link">умовами обробки персональних</a> даних та приймаю їх.
                </div>
            </div>

            <div class="flex flex-row justify-center mt-6">
                <x-ui::button before="rocket-launch" x-bind:disabled="!policy">Створити замовлення</x-ui::button>
            </div>
        </form>
    @else
        <x-ui::empty text="Здається у вашому кошику не залишилося товарів" />
    @endif
</x-ui::section>
