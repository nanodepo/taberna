<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Product;

new
#[Layout('layouts.shop')]
class extends Component {
    public Category $category;
    public Product $product;

    public function with(): array
    {
        return [
            'attributes' => $this->product->attributes->map(fn($a) => literal(
                key: $a->name,
                option: Option::find($a->pivot->option_id)?->name,
                value: $a->pivot->value
            ))

        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::breadcrumbs class="mb-6">
                <x-ui::breadcrumbs.item :href="route('home')">Home</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('categories')">Categories</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('category', $category->slug)">{{ $category->name }}</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item active>{{ $product->name }}</x-ui::breadcrumbs.item>
            </x-ui::breadcrumbs>

            <div
                x-data="{ imageUrl: '' }"
                x-init="imageUrl = '{{ $product->image?->thumbnail() ?? asset('images/500.svg') }}'"
                class="flex flex-col"
            >
                <div class="flex flex-col justify-center items-center -mx-6 -mt-3 mb-3">
                    <div class="w-full aspect-square bg-center bg-cover" x-bind:style="{ backgroundImage: 'url(' + imageUrl + ')' }"></div>
                </div>

                <div class="flex flex-row flex-wrap justify-center gap-1 mb-6">
                    @foreach($product->images as $image)
                        <div x-on:click="imageUrl = '{{ $image?->thumbnail() }}'" class="w-12 h-12 rounded-lg border border-section-separator bg-hint bg-cover bg-center cursor-pointer" style="background-image: url('{{ $image?->thumbnail(96) }}')"></div>
                    @endforeach
                </div>
            </div>

            <x-ui::header :title="$product->name" class="mb-6" />

            <x-ui::list>
                <x-ui::list.accordion
                    icon="information-circle"
                    title="Опис товару"
                    :subtitle="$product->intro"
                >
                    <div class="p-6">{!! str($product->description)->markdown() !!}</div>
                </x-ui::list.accordion>

                <x-ui::list.accordion
                    icon="adjustments-horizontal"
                    title="Характеристики"
                >
                    <div class="flex flex-col divide-y divide-section-separator">
                        @foreach($attributes as $attr)
                            <x-ui::list.value :title="$attr->key">{{ $attr->option ?? $attr->value }}</x-ui::list.value>
                        @endforeach
                    </div>
                </x-ui::list.accordion>

                <x-ui::list.value icon="qr-code" title="SKU" description="Код товару">
                    {{ $product->sku }}
                </x-ui::list.value>

                <x-ui::list.value icon="banknotes" title="Ціна" subtitle="При замовленні від 5 товарів" accent>
                    {{ price($product->price - $product->partner_price)->formatted() }}
                </x-ui::list.value>
            </x-ui::list>

        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
