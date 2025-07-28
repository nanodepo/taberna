<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('profile.show')">Особистий кабінет</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Редагування профілю</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.profile-info')
    </x-slot>

    <x-slot name="right">
        @livewire('sections.profile-password')
    </x-slot>
</x-ui::layout.double>
