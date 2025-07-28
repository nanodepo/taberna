<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    use HasAlert;

    public string $id;

    #[Validate(['nullable', 'string', 'max:250'])]
    public string $title = '';
    #[Validate(['nullable', 'string', 'max:5000'])]
    public string $description = '';

    public function mount(Category $category): void
    {
        $this->id = $category->id;
        $this->init($category);
    }

    public function init(Category $category): void
    {
        $this->title = $category->title ?? '';
        $this->description = $category->description ?? '';
    }

    public function submit(): void
    {
        $this->validate();

        Category::query()->where('id', $this->id)->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->init(Category::find($this->id));

        $this->alert('Сохранено');
    }
} ?>

<x-ui::section header="Контент" hint="Используется как статья в конце страницы">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="title" label="Заголовок страницы" max="250">
            <x-ui::input x-model="field" max="250" maxlength="250" />
        </x-ui::field>

        <x-ui::field wire:model="description" label="Описание" max="5000">
            <x-ui::input.textarea x-model="field" rows="8" max="5000" maxlength="5000" />
        </x-ui::field>

        <div class="flex flex-row justify-end">
            <x-ui::button type="submit" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
