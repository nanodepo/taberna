<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    public Category $category;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:64'])]
    public string $slug = '';
    #[Validate(['nullable', 'string', 'max:250'])]
    public string $title = '';
    #[Validate(['nullable', 'string', 'max:5000'])]
    public string $description = '';
    #[Validate(['required', 'bool'])]
    public bool $is_active = true;
    #[Validate(['required', 'bool'])]
    public bool $is_virtual = false;

    public function mount(): void
    {
        $this->name = $this->category->name;
        $this->slug = $this->category->slug;
        $this->title = $this->category->title ?? '';
        $this->description = $this->category->description ?? '';
        $this->is_active = $this->category->is_active;
        $this->is_virtual = $this->category->is_virtual;
    }

    public function submit(): void
    {
        $data = $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => str(empty($this->slug) ? $this->name : $this->slug)->slug()->value(),
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_virtual' => $this->is_virtual,
        ]);

        $this->redirectRoute('category.show', $this->category->id);
    }
} ?>

<x-ui::layout.double>
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
            <x-ui::breadcrumbs.item :href="route('category.show', $category->id)">{{ $category->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Редактирование</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.category-name', ['category' => $category])

        @livewire('sections.image-uploader', ['subject' => $category])
    </x-slot>

    <x-slot name="right">
        @livewire('sections.category-content', ['category' => $category])

        @livewire('sections.seo-manager', ['subject' => $category])
    </x-slot>
</x-ui::layout.double>
