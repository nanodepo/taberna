<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    public Category $category;

    #[Validate(['required', 'string', 'max:120'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:36'])]
    public string $sku = '';
    #[Validate(['required', 'ulid'])]
    public string $brand = '';

    public function submit(): void
    {
        $this->validate();

        $product = Product::query()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => 1,
        ]);

        $this->category->attributes->each(function (Attribute $attribute) use ($product) {
            $product->attributes()->attach($attribute->id, [
                'value' => $attribute->feature->default_value,
                'option_id' => $attribute->feature->option_id,
            ]);
        });

        $this->redirectRoute('product.edit', $product->id);
    }

    public function with(): array
    {
        return [
            'brands' => Brand::query()->select('id', 'name')->get(),
        ];
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.index')">Категории</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.show', $category->id)">{{ $category->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Добавление товара</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section title="Добавление товара">
            <form wire:submit="submit" class="flex flex-col gap-3">
                <x-ui::field wire:model="name" label="Введите название" max="120">
                    <x-ui::input x-model="field" max="120" maxlength="120" required />
                </x-ui::field>

                <x-ui::field wire:model="sku" label="SKU" hint="Код товара" max="36">
                    <x-ui::input x-model="field" max="36" maxlength="36" required />
                </x-ui::field>

                <x-ui::field label="Бренд" hint="Компания производитель">
                    <x-ui::input.select wire:model="brand" required>
                        <x-ui::input.select.item value="">- - - Выберите - - -</x-ui::input.select.item>
                        @foreach($brands as $brand)
                            <x-ui::input.select.item :value="$brand->id">{{ $brand->name }}</x-ui::input.select.item>
                        @endforeach
                    </x-ui::input.select>
                </x-ui::field>

                <div class="flex flex-row justify-end">
                    <x-ui::button type="submit">Сохранить</x-ui::button>
                </div>
            </form>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
