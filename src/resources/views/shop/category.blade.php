<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;

new
#[Layout('layouts.shop')]
class extends Component {
    public function with(): array
    {
        return [
            'categories' => Category::query()->whereNull('category_id')->get(),
        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::header title="Categories" subtitle="Lorem ipsum dolor sit amet" class="my-12" />

            <x-taberna::menubar />

        </x-ui::section>

        <div class="flex flex-row flex-wrap mx-1.5 sm:-mx-1.5">
            @foreach($categories as $category)
                <div class="flex flex-col w-1/2 p-1.5">
                    <x-ui::card
                        :image="$category->image?->thumbnail(500) ?? asset('images/500.svg')"
                        :title="$category->name"
                        :subtitle="$category->intro"
                        :href="route('category', $category->slug)"
                    />
                </div>
            @endforeach
        </div>

    </x-slot>

</x-ui::layout.single>
