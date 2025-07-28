<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use WithModal;

    public Product $product;

    public bool $active = false;
    public bool $has_variants = false;

    public function mount(): void
    {
        $this->active = $this->product->is_active;
        $this->has_variants = $this->product->has_variants;
    }

    public function updatedActive(bool $val): void
    {
        $this->product->update(['is_active' => $val]);
        $this->product->refresh();
    }

    public function updatedHasVariants(bool $val): void
    {
        $this->product->update(['has_variants' => $val]);
        $this->product->refresh();
    }

    public function with(): array
    {
        return [
            'attributes' => $this->product->attributes->map(fn($a) => literal(
                key: $a->name,
                option: Option::find($a->pivot->option_id)?->name,
                value: $a->pivot->value
            )),
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.index')">Категории</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.show', $product->category->id)">{{ $product->category->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>{{ $product->name }}</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        <x-ui::section>
            <div class="flex flex-col justify-center items-center my-6">
                <div class="w-36 h-36 bg-center bg-cover rounded-full" style="background-image: url('{{ $product->image?->thumbnail(360) ?? asset('images/500.svg') }}')"></div>
            </div>

            <div class="flex flex-row flex-wrap justify-center gap-1 mb-6">
                @foreach($product->images as $image)
                    <div class="w-12 h-12 rounded-lg border border-section-separator bg-hint bg-cover bg-center" style="background-image: url('{{ $image?->thumbnail(96) }}')"></div>
                @endforeach
            </div>

            <x-ui::list>
                <x-ui::list.accordion
                    icon="information-circle"
                    :title="$product->name"
                    :subtitle="$product->description"
                >
                    <div class="p-6">{{ $product->description }}</div>
                </x-ui::list.accordion>

                <x-ui::list.accordion
                    icon="adjustments-horizontal"
                    title="Характеристики"
                >
                    <div class="flex flex-col divide-y divide-section-separator">
                        @foreach($attributes as $attr)
                            <x-ui::list.value :title="$attr->key">{{ $attr->option ?? $attr->value }}</x-ui::list.value>
                        @endforeach
                    </div>
                </x-ui::list.accordion>

                <x-ui::list.value icon="qr-code" title="SKU" description="Код товара">
                    {{ $product->sku }}
                </x-ui::list.value>

                <x-ui::list.value icon="banknotes" title="Цена" description="Без учета скидки">
                    {{ price($product->price)->formatted() }}
                </x-ui::list.value>

                <x-ui::list.value icon="receipt-percent" title="Скидка">
                    {{ $product->discount }}
                </x-ui::list.value>

                <x-ui::list.value icon="square-3-stack-3d" title="Количество">
                    {{ $product->quantity }}
                </x-ui::list.value>

                <x-ui::list.value icon="globe-europe-africa" title="Бренд">
                    {{ $product->brand->name }}
                </x-ui::list.value>

                <x-ui::list.value
                    icon="{{ $product->is_active ? 'eye' : 'eye-slash' }}"
                    title="Статус отображения"
                    subtitle="{{ $product->is_active ? 'Отображается' : 'Скрыт' }}"
                >
                    <x-ui::input.switch wire:model.live="active" />
                </x-ui::list.value>

                <x-ui::list.value
                    icon="squares-plus"
                    title="Варианты товара"
                    subtitle="{{ $product->has_variants ? 'Включены' : 'Не используются' }}"
                >
                    <x-ui::input.switch wire:model.live="has_variants" />
                </x-ui::list.value>

                <x-ui::list.button :href="route('product.edit', $product->id)" icon="pencil" title="Редактировать" />

                <x-ui::list.button icon="trash" title="Удалить" destructive />
            </x-ui::list>
        </x-ui::section>

        @livewire('sections.addon-list', ['product' => $product])
    </x-slot>

    <x-slot name="right">
        @if($product->has_variants)
            @livewire('sections.variant-list', ['product' => $product])

            @livewire('sections.variant-maker', ['product' => $product])
        @endif
    </x-slot>
</x-ui::layout.double>
