<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    public ?Category $category = null;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';

    #[Validate(['required', 'bool'])]
    public bool $is_virtual = false;

    public function submit(): void
    {
        $data = $this->validate();

        $category = Category::query()->create([
            'category_id' => $this->category?->id,
            'name' => $this->name,
            'slug' => str($this->name)->slug()->value(),
            'is_virtual' => $this->is_virtual,
        ]);

        $this->redirectRoute('category.edit', $category->id);
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.index')">Категории</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            @if($category?->parent)
                <x-ui::breadcrumbs.item :href="route('category.show', $category->parent->id)">{{ $category->parent->name }}</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
            @endif
            <x-ui::breadcrumbs.item active>Добавление</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section title="Добавление категории">
            <form wire:submit="submit" class="flex flex-col gap-3">
                <x-ui::field wire:model="name" label="Введите название" hint="Используйте короткие названия" max="64">
                    <x-ui::input x-model="field" max="64" maxlength="64" required />
                </x-ui::field>

                <div class="flex flex-row items-center justify-between gap-3">
                    <x-ui::title title="Виртуальная категория" subtitle="Будет эта категория обычной или виртуальной?" />

                    <x-ui::input.switch wire:model="is_virtual" />
                </div>

                <div class="flex flex-row justify-end">
                    <x-ui::button type="submit">Сохранить</x-ui::button>
                </div>
            </form>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
