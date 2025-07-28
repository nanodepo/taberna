<?php

use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use HasAlert;

    public function delete(string $id): void
    {
        session()->remove('basket.' . $id);
        $this->alert('Товар видалено');
        $this->redirectRoute('basket');
    }

    public function with(): array
    {
//        dd(session()->get('basket'));

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

        return [
            'items' => $items,
        ];
    }
} ?>

<x-ui::section>
    @if($items->isNotEmpty())
        <x-ui::title
            title="Обрані товари"
            subtitle="Товари та доповнення, які ви обрали"
            class="mb-3"
        />

        <x-ui::list>
            @foreach($items as $item)
                <x-ui::list.value
                    :subhead="is_null($item->variant) ? price($item->product->price * $item->quantity)->formatted() : price($item->variant->price * $item->quantity)->formatted()"
                    :title="is_null($item->variant) ? $item->product->name : $item->product->name .' (' . $item->variant->options->pluck('name')->join('/').')'"
                    subtitle="Кількість: {{ $item->quantity }} | Знижка: {{ price($item->variant->discount ?? $item->product->discount)->formatted() }}"
                >
                    <x-ui::circle icon="trash" wire:click="delete('{{ $item->id }}')" color="destructive" />
                </x-ui::list.value>

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

        <div
            x-data=""
            x-on:paymentselected.window="
                console.log('begin_checkout');
                gtag('event', 'begin_checkout', {
                    currency: 'UAH',
                    value: {{ $items->sum('price') }},
                    items: @js($items->map(fn ($item) => [
                        'item_id' => is_null($item->variant) ? $item->product->sku : $item->variant->sku,
                        'item_name' => is_null($item->variant) ? $item->product->name : $item->product->name .' (' . $item->variant->options->pluck('name')->join('/').')',
                        'price' => is_null($item->variant) ? ($item->product->price * $item->quantity) : ($item->variant->price * $item->quantity)
                    ])->toArray())
                });
            "
            class="flex flex-row justify-between mt-6"
        >
            <x-ui::title
                title="До сплати"
                subtitle="Сума вказана з урахуванням знижки"
            />

            <div class="flex-none text-2xl text-accent font-medium">
                {{ price($items->sum('price'))->formatted() }}
            </div>
        </div>
    @else
        <x-ui::empty text="Ваш кошик порожній, спочатку додайте до нього товари" />
    @endif
</x-ui::section>
