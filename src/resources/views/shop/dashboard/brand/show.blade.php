<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Brand;

new class extends Component {
    use HasAlert;

    public Brand $brand;

    #[Validate(['required', 'string', 'max:128'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:128'])]
    public string $slug = '';

    public function mount(): void
    {
        $this->name = $this->brand->name;
        $this->slug = $this->brand->slug;
    }

    public function submit(): void
    {
        $this->validate();

        $this->brand->update([
            'name' => $this->name,
            'slug' => str($this->slug)->slug(),
        ]);

        $this->alert('Сохранено');
    }

    public function delete(): void
    {
        $this->brand->delete();
        $this->redirectRoute('brand.index');
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('brand.index')">Бренды</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>{{ $brand->name }}</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section title="Информация о бренде">
            <form wire:submit="submit" class="flex flex-col gap-3">
                <x-ui::field wire:model="name" label="Название" hint="Используйте простые короткие названия" max="128">
                    <x-ui::input x-model="field" max="128" maxlength="128" required />
                </x-ui::field>

                <x-ui::field wire:model="slug" label="Slug" hint="Название латиницей без пробелов - нужно для формирования красивого URL" max="128">
                    <x-ui::input x-model="field" max="128" maxlength="128" required />
                </x-ui::field>

                <div class="flex flex-row justify-end">
                    <x-ui::button type="submit" wire:dirty>Сохранить</x-ui::button>
                </div>
            </form>
        </x-ui::section>

        <x-ui::section hint="Товары принадлежащие этому бренду продолжат отображаться на сайте, но из списка производителей он исчезнет" destructive>
            <div class="flex flex-row items-center justify-between">
                <x-ui::title
                    title="Удаление бренда"
                    subtitle="Имейте в виду, отменить это действие будет невозможно"
                    destructive
                />

                <x-ui::button
                    wire:click="delete"
                    wire:confirm="Вы действительно хотите удалить этот бренд?"
                    color="destructive"
                >Удалить
                </x-ui::button>
            </div>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
