<?php

use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Attribute;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Attribute $attribute;
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('group.index')">Характеристики</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('group.index')">{{ $attribute->group->title }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>{{ $attribute->name }}</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.attribute-settings', ['attribute' => $attribute])
    </x-slot>

    <x-slot name="right">
        @livewire('sections.attribute-options', ['attribute' => $attribute])
    </x-slot>
</x-ui::layout.double>
