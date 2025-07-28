<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Addon;

new class extends Component {
    public function with(): array
    {
        return [
            'addons' => Addon::query()->get(),
        ];
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Дополнения</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section>
            <div class="flex flex-row justify-between items-center mb-3">
                <x-ui::title
                    title="Дополнения"
                    subtitle="То что можно купить в дополнение к кровати"
                />

                <x-ui::button :href="route('addon.create')" before="plus">Добавить</x-ui::button>
            </div>

            <x-ui::list>
                @foreach($addons as $addon)
                    <x-ui::list.double
                        before="squares-plus"
                        :title="$addon->name"
                        :description="$addon->description"
                        after="pencil"
                        :href="route('addon.show', $addon->id)"
                    />
                @endforeach
            </x-ui::list>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
