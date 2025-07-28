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

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::header title="Basket" subtitle="Lorem ipsum dolor sit amet" class="my-12" />

            <x-taberna::menubar />

        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
