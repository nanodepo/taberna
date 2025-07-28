<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    public Category $category;

    public bool $active = false;

    public function mount(): void
    {
        $this->active = $this->category->is_active;
    }

    public function updatedActive($value): void
    {
        $this->category->update(['is_active' => $value]);
    }

    public function delete(): void
    {
        $parent = $this->category->category_id;

        $this->category->delete();

        if ($parent) {
            $this->redirectRoute('category.show', $parent);
        } else {
            $this->redirectRoute('category.index');
        }
    }

    public function with(): array
    {
        return [];
    }
} ?>

<x-ui::section hint="Подробная информация о категории">
    <div class="flex flex-col justify-center items-center my-6">
        <div class="w-36 h-36 bg-center bg-cover rounded-full" style="background-image: url('{{ $category->image?->thumbnail(360) ?? asset('images/500.svg') }}')"></div>
    </div>

    <x-ui::list>
        <x-ui::list.accordion
            icon="information-circle"
            :title="$category->name"
            :subtitle="$category->description"
        >
            <div class="p-6">{{ $category->description }}</div>
        </x-ui::list.accordion>

        <x-ui::list.value icon="sparkles" title="Тип категории">
            {{ $category->is_virtual ? 'Виртуальная' : 'Обычная' }}
        </x-ui::list.value>

        <x-ui::list.value
            icon="{{ $category->is_active ? 'eye' : 'eye-slash' }}"
            title="Статус отображения"
            subtitle="{{ $category->is_active ? 'Отображается' : 'Скрыта' }}"
        >
            <x-ui::input.switch wire:model.live="active" />
        </x-ui::list.value>

        <x-ui::list.value icon="folder-plus" title="Подкатегорий">
            {{ $category->children->count() }}
        </x-ui::list.value>

        <x-ui::list.value icon="rectangle-stack" title="Товаров">
            {{ $category->is_virtual ? $category->virtual->count() : $category->products->count() }}
        </x-ui::list.value>

        <x-ui::list.value :href="route('category.attribute', $category->id)" icon="adjustments-horizontal" title="Характеристики">
            <x-ui::chip before="cog-6-tooth" title="Настроить" />
        </x-ui::list.value>

        <x-ui::list.button :href="route('category.edit', $category->id)" icon="pencil" title="Редактировать" />

        <x-ui::list.button
            wire:click="delete"
            wire:confirm="Вы действительно хотите удалить категорию?"
            icon="trash"
            title="Удалить"
            destructive
        />
    </x-ui::list>
</x-ui::section>
