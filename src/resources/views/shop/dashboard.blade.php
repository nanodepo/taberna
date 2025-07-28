<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [];
    }
} ?>

<x-ui::layout>
    <x-slot name="content">
        <x-ui::section>
            Dashboard
        </x-ui::section>
    </x-slot>
</x-ui::layout>
