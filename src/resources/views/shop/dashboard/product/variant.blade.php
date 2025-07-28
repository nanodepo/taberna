<?php

use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use WithModal;

    public Product $product;
    public Variant $variant;

    public string $sku = '';
    public int $price = 0;
    public int $discount = 0;
    public int $quantity = 0;
    public bool $is_active = true;

    public function mount(): void
    {
        $this->sku = $this->variant->sku;
        $this->price = $this->variant->price;
        $this->discount = $this->variant->discount;
        $this->quantity = $this->variant->quantity;
        $this->is_active = $this->variant->is_active;
    }

    public function delete(): void
    {
        $this->variant->options()->detach();
        $this->variant->delete();
        $this->redirectRoute('product.show', $this->product->id);
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
            <x-ui::breadcrumbs.item :href="route('product.show', $product->id)">{{ $product->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Редактирование варианта</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.variant-settings', ['variant' => $variant])

        @livewire('sections.product-settings', ['subject' => $variant])
    </x-slot>

    <x-slot name="right">
        @livewire('sections.image-uploader', ['subject' => $variant])

        <x-ui::section hint="С точки зрения SEO удаление товаров часто приводит к ошибкам индексации, так что делайте это с осторожностью" destructive>
            <div class="flex flex-row items-center justify-between">
                <x-ui::title
                    title="Удаление варианта"
                    subtitle="Имейте в виду, отменить это действие будет невозможно"
                    destructive
                />

                <x-ui::button
                    wire:click="delete"
                    wire:confirm="Вы действительно хотите удалить этот вариант товара?"
                    color="destructive"
                >Удалить
                </x-ui::button>
            </div>
        </x-ui::section>
    </x-slot>
</x-ui::layout.double>
