<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Category;

new class extends Component {
    use HasAlert;

    public string $id;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['nullable', 'string', 'max:64'])]
    public string $slug = '';

    public function mount(Category $category): void
    {
        $this->id = $category->id;
        $this->init($category);
    }

    public function init(Category $category): void
    {
        $this->name = $category->name;
        $this->slug = $category->slug;
    }

    public function submit(): void
    {
        $this->validate();

        Category::query()->where('id', $this->id)->update([
            'name' => $this->name,
            'slug' => str(empty($this->slug) ? $this->name : $this->slug)->slug()->value(),
        ]);

        $this->init(Category::find($this->id));

        $this->alert('Сохранено');
    }
} ?>

<x-ui::section header="Название категории" hint="Slug - это название категории написанное латиницей, где все пробелы заменены на тире. Используется для формирования красивого URL страницы.">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="name" label="Введите название" hint="Не советуем использовать длинные названия" max="64">
            <x-ui::input x-model="field" max="64" maxlength="64" required />
        </x-ui::field>

        <x-ui::field wire:model="slug" label="Slug" hint="Вы можете оставить это поле пустым" max="64">
            <x-ui::input x-model="field" max="64" maxlength="64" />
        </x-ui::field>

        <div class="flex flex-row justify-end">
            <x-ui::button type="submit" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
