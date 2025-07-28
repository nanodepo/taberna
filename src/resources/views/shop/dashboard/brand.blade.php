<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Brand;

new class extends Component {
    use WithModal;

    #[Validate(['required', 'string', 'max:128'])]
    public string $name = '';

    public function add(): void
    {
        $this->open();
    }

    public function updatedOpened($val): void
    {
        if (!$val) {
            $this->reset();
        }
    }

    public function submit(): void
    {
        $this->validate();

        $brand = Brand::query()->create([
            'name' => $this->name,
            'slug' => str($this->name)->slug(),
        ]);

        $this->redirectRoute('brand.show', $brand->id);
    }

    public function with(): array
    {
        return [
            'brands' => Brand::query()->orderBy('name')->get(),
        ];
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Бренды</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section>
            <div class="flex flex-row justify-between items-center mb-3">
                <x-ui::title
                    title="Бренды"
                    subtitle="Производители товаров"
                />

                <x-ui::button wire:click="add" before="plus">Добавить</x-ui::button>
            </div>

            <x-ui::list>
                @foreach($brands as $brand)
                    <x-ui::list.double
                        before="globe-europe-africa"
                        :title="$brand->name"
                        :description="$brand->slug"
                        after="arrow-long-right"
                        :href="route('brand.show', $brand->id)"
                    />
                @endforeach
            </x-ui::list>
        </x-ui::section>

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title title="Добавление" />
                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-col gap-3" x-trap="modal">
                    <x-ui::field wire:model="name" label="Название" hint="Используйте простые короткие названия" max="128">
                        <x-ui::input x-model="field" max="128" maxlength="128" required />
                    </x-ui::field>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" color="secondary" variant="text">Отмена</x-ui::button>
                <x-ui::button wire:click="submit">Сохранить</x-ui::button>
            </x-slot>
        </x-ui::dialog>
    </x-slot>
</x-ui::layout.single>
