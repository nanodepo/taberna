<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new
#[On('variant-created')]
#[On('variant-updated')]
#[On('variant-deleted')]
class extends Component {
    public string $productId;
    public string $productName;
    public int $productAttr = 0;

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->productAttr = $product->attributes->where('is_variant_defining', true)->count();
    }

    public function with(): array
    {
        return [
            'variants' => Variant::query()->with('options')->where('product_id', $this->productId)->get(),
        ];
    }
} ?>

<x-ui::section>
    <div class="flex flex-row justify-between items-center mb-3">
        <x-ui::title title="Варианты товара" subtitle="Пользователь может выбрать один из вариантов" />

        <x-ui::button x-on:click="$dispatch('add-variant')" before="plus">Добавить</x-ui::button>
    </div>

    <x-ui::list>
        @forelse($variants as $variant)
            <x-ui::list.item
                :title="$productName . ' (' . $variant->options->pluck('name')->join('/').')'"
                :href="route('product.variant', ['product' => $variant->product_id, 'variant' => $variant->id])"
            >
                <x-slot name="before">
                    <x-ui::avatar :url="$variant->image?->thumbnail(96)" icon="cube" class="w-12 h-12" />
                </x-slot>

                <x-slot name="subtitle">
                    <div class="flex flex-row gap-3">
                        @if($productAttr > $variant->options->count())
                            <x-ui::meta icon="exclamation-triangle" destructive />
                        @endif
                        <x-ui::meta icon="hashtag" :text="$variant->sku" />
                        <x-ui::meta icon="banknotes" :text="$variant->price" />
                        <x-ui::meta icon="receipt-percent" :text="$variant->discount > 0 ? $variant->discount : 'O'" />
                        <x-ui::meta icon="square-3-stack-3d" :text="$variant->quantity > 0 ? $variant->quantity : 'O'" />
                    </div>
                </x-slot>

                <x-slot name="after" class="text-hint">
                    <x-icon::arrow-long-right />
                </x-slot>
            </x-ui::list.item>
        @empty
            <x-ui::empty />
        @endforelse
    </x-ui::list>
</x-ui::section>
