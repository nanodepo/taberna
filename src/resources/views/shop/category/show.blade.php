<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

new
#[Layout('layouts.shop')]
class extends Component {
    public Category $category;

    public function with(): array
    {
        return [
            'products' => Product::all(),
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
                <x-ui::breadcrumbs.item :href="route('categories')">Categories</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item active>{{ $category->name }}</x-ui::breadcrumbs.item>
            </x-ui::breadcrumbs>

            <x-ui::header :title="$category->name" subtitle="Lorem ipsum dolor sit amet" class="my-12" />

            <x-taberna::menubar />

        </x-ui::section>

        <div class="flex flex-row items-center justify-between">
            <x-ui::circle icon="funnel" />

            <form class="relative w-48">
                <input
                    type="text"
                    name="search"
                    wire:model="search"
                    placeholder="Search"
                    class="input round w-full"
                />
                <x-ui::circle type="submit" icon="fire" class="absolute" style="top: 3px; right: 3px;" />
            </form>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap">
            @foreach($products as $product)
                <div class="flex flex-col w-full sm:w-1/2 p-3 sm:p-1.5">
                    <x-ui::card
                        :image="$product->image?->thumbnail(500) ?? asset('images/500.svg')"
                        :title="$product->name"
                        :subtitle="$product->intro"
                        :href="route('product', [$product->category->slug, $product->sku])"
                    >
                        <x-slot name="footer">
                            <div class="text-xl font-medium text-secondary">{{ price($product->price)->formatted() }}</div>
                            <x-ui::circle icon="shopping-bag" variant="filled" />
                        </x-slot>
                    </x-ui::card>
                </div>
            @endforeach
        </div>

        <x-ui::section class="mt-6">
            <x-ui::header
                :title="$category->title"
                :subtitle="$category->description"
                class="my-6"
            />
        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
