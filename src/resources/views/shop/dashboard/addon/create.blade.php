<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Addon;

new class extends Component {

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';

    #[Validate(['nullable', 'string', 'max:250'])]
    public string $description = '';

    #[Validate(['required', 'integer', 'min:1'])]
    public int $price = 1;

    #[Validate(['nullable', 'string', 'max:64'])]
    public string $code = '';

    #[Validate(['required', 'integer', 'min:1'])]
    public int $max_quantity = 1;

    #[Validate(['required', 'bool'])]
    public bool $is_active = true;

    public function submit(): void
    {
        $this->validate();

        Addon::query()->create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'code' => empty($this->code) ? str($this->name)->slug() : str($this->code)->slug(),
            'max_quantity' => $this->max_quantity,
            'is_active' => $this->is_active,
        ]);

        $this->redirectRoute('addon.index');
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('addon.index')">Дополнения</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Добавление</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section title="Добавление дополнения">
            <form wire:submit="submit" class="flex flex-col gap-3">
                <x-ui::field wire:model="name" label="Введите название" hint="Используйте короткие названия" max="64">
                    <x-ui::input x-model="field" max="64" maxlength="64" required />
                </x-ui::field>

                <x-ui::field wire:model="code" label="Уникальный идентификатор" hint="Можно оставить пустым, если не знаете зачем это" max="64">
                    <x-ui::input x-model="field" max="64" maxlength="64" />
                </x-ui::field>

                <x-ui::field wire:model="description" label="Краткое описание" hint="Пишите просто и понятно" max="250">
                    <x-ui::input.textarea x-model="field" max="250" maxlength="250" rows="3" />
                </x-ui::field>

                <div class="flex flex-row w-full">
                    <div class="flex flex-col flex-auto w-1/2 pr-1.5">
                        <x-ui::field label="Цена">
                            <x-ui::input type="number" wire:model="price" required />
                        </x-ui::field>
                    </div>

                    <div class="flex flex-col flex-auto w-1/2 pl-1.5">
                        <x-ui::field label="Макс. количество">
                            <x-ui::input wire:model="max_quantity" required />
                        </x-ui::field>
                    </div>
                </div>

                <div class="flex flex-row items-center justify-between gap-3">
                    <x-ui::title title="Статус" subtitle="Дополнения можно включать/отключать" />
                    <x-ui::input.switch wire:model="is_active" />
                </div>

                <div class="flex flex-row justify-end">
                    <x-ui::button type="submit">Сохранить</x-ui::button>
                </div>
            </form>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
