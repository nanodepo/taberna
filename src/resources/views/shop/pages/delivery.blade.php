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

            <x-ui::breadcrumbs>
                <x-ui::breadcrumbs.item :href="route('home')">Home</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item active>Delivery</x-ui::breadcrumbs.item>
            </x-ui::breadcrumbs>

            <x-ui::header title="Delivery" subtitle="Lorem ipsum dolor sit amet" class="my-6" />

        </x-ui::section>

        <x-ui::section>
            <div class="flex flex-col gap-3 markdown">
                {!! str(file_get_contents(resource_path('markdown/delivery.md')))->markdown() !!}
            </div>
        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
