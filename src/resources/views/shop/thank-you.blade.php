<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.shop')]
class extends Component {
    public function with(): array
    {
        return [
            //
        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section>
            // Thank You
        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
