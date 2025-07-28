<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use HasAlert;

    public string $id;
    public string $type;

    #[Validate(['required', 'string', 'max:120'])]
    public string $name;
    #[Validate(['required', 'string', 'max:120'])]
    public ?string $prefix;
    #[Validate(['nullable', 'string', 'max:500'])]
    public ?string $intro = null;
    #[Validate(['nullable', 'string', 'max:5000'])]
    public ?string $description = null;

    public function mount(Product $subject): void
    {
        $this->id = $subject->id;
        $this->type = $subject::class;

        $this->name = $subject->name;
        $this->prefix = $subject->prefix;
        $this->intro = $subject->intro;
        $this->description = $subject->description;
    }

    public function submit(): void
    {
        $this->validate();

        Product::query()->where('id', $this->id)->update([
            'name' => $this->name,
            'prefix' => $this->prefix,
            'intro' => $this->intro,
            'description' => $this->description,
        ]);

        $this->alert('Сохранено');
    }
} ?>

<x-ui::section header="Основные данные" hint="Название и описание (если актуально)">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="name" label="Введите название" max="120">
            <x-ui::input x-model="field" max="120" maxlength="120" required />
        </x-ui::field>

        <x-ui::field wire:model="prefix" label="Префикс" max="120" hint="Используется на странице товара чтобы расширить название">
            <x-ui::input x-model="field" max="120" maxlength="120" />
        </x-ui::field>

        <x-ui::field wire:model="intro" label="Краткое описание" max="250">
            <x-ui::input.textarea x-model="field" rows="3" max="250" maxlength="250" />
        </x-ui::field>

        <x-ui::field wire:model="description" label="Описание товара" hint="Можно использовать Markdown" max="5000">
            <x-ui::input.textarea x-model="field" rows="6" max="5000" maxlength="5000" />
        </x-ui::field>

        <div class="flex flex-row justify-end">
            <x-ui::button type="submit" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
