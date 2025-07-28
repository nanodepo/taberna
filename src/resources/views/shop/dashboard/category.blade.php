<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    public function with(): array
    {
        return [
            'categories' => Category::query()
                ->with([
                    'children:id,category_id,slug,name,is_virtual',
                ])
                ->withCount('products')
                ->whereNull('category_id')
                ->get(),
        ];
    }
} ?>

<x-ui::layout>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Категории</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section>
            <div class="flex flex-row justify-between items-center mb-3">
                <x-ui::title
                    title="Категории"
                    subtitle="Разделяйте товары на категории"
                />

                <x-ui::button :href="route('category.create')" before="folder-plus">Добавить</x-ui::button>
            </div>

            <x-ui::list>
                @foreach($categories as $category)
                    <x-taberna::tree-category-item :category="$category" />
                @endforeach
            </x-ui::list>
        </x-ui::section>
    </x-slot>
</x-ui::layout>
