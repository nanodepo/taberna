<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new
#[Layout('layouts.shop')]
class extends Component {
    public Category $category;
    public Product $product;

    public function with(): array
    {
        if ($this->product->has_variants && $this->product->variants->isNotEmpty()) {
            $ids = $this->product->attributes->where('is_variant_defining', true)->pluck('pivot.option_id')->toArray();
            $baseVariant = Variant::query()
                ->whereHas('options', function ($q) use ($ids) {
                    $q->whereIn('options.id', $ids);
                }, '=', count($ids))
                ->first();
            if (is_null($baseVariant)) {
                $baseVariant = $this->product->variants()->first();
            }
        }


        return [
//            'attributes' => $this->product->attributes->map(fn($a) => literal(
//                key: $a->name,
//                option: Option::find($a->pivot->option_id)?->name,
//                value: $a->pivot->value
//            )),
            'currentVariant' => $baseVariant ?? null,
            'attrs' => $this->product->has_variants && $this->product->variants->isNotEmpty() ? $this->product->attributes()
                ->withWhereHas('options', function ($q) {
                    $q->withWhereHas('variants', function ($query) {
                        $query->where('product_id', $this->product->id);
                    });
                })
                ->where('is_variant_defining', true)
                ->get()
                // Мы перебираем аттрибуты чтобы определить ссылки для каждой опции с учетом выбранных в данный момент аттрибутов
                ->map(function ($attr) use ($baseVariant) {
                    // Перебираем каждую опцию и пытаемся понять на какой вариант товара она будет вести
                    $opts = $attr->options->map(function ($option) use ($baseVariant, $attr) {
                        // Определяем идентификаторы опций которые нужны
                        $ids = $baseVariant?->options->where('attribute_id', '!=', $attr->id)->pluck('id')->toArray();
                        $ids[] = $option->id;

                        // Получаем вариант который в себе содержит все нужные нам опции
                        $variant = Variant::query()
                            ->whereHas('options', function ($q) use ($ids) {
                                $q->whereIn('options.id', $ids);
                            }, '=', count($ids))
                            ->first();

                        // Генерируем простые std классы для максимально сжатого результата
                        return literal(
                            id: $option->id,
                            code: $option->code,
                            name: $option->name,
                            variant: $variant?->sku,
                        );
                    });

                    return literal(
                        id: $attr->id,
                        type: $attr->type,
                        code: $attr->code,
                        name: $attr->name,
                        options: $opts,
                    );
                }) : [],

        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::breadcrumbs class="mb-6">
                <x-ui::breadcrumbs.item :href="route('home')" wire:navigate>Home</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('categories')" wire:navigate>Categories</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('category', $category->slug)" wire:navigate>{{ $category->name }}</x-ui::breadcrumbs.item>
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

                @if($product->images->count() > 1)
                    <div class="flex flex-row flex-wrap justify-center gap-1 mb-6">
                        @foreach($product->images as $image)
                            <div x-on:click="imageUrl = '{{ $image?->thumbnail() }}'" class="relative w-12 h-12 rounded-lg border border-section-separator bg-hint bg-cover bg-center cursor-pointer" style="background-image: url('{{ $image?->thumbnail(96) }}')">
                                <div x-show="imageUrl === '{{ $image?->thumbnail() }}'" class="absolute inset-0 flex flex-col justify-center items-center bg-secondary-container/50 text-on-secondary-container">
                                    <x-icon::eye type="solid" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <x-ui::header :title="$product->name" :subtitle="$product->sku" class="mb-6" />

            <x-ui::list>
                <x-ui::list.value icon="banknotes" title="Price" accent>
                    {{ price($product->price - $product->partner_price)->formatted() }}
                </x-ui::list.value>

                <x-ui::list.value icon="square-3-stack-3d" title="Quantity">
                    <x-ui::input.counter min="1" :max="$product->quantity" />
                </x-ui::list.value>

                @if($product->has_variants)
                    @php
                        $current = $variant ?? $currentVariant;
                    @endphp
                    @foreach($attrs as $attribute)

                        <div class="group item items-center">
                            <div class="flex flex-col justify-center items-center flex-none w-6 h-6">
                                <x-icon::circle type="micro" />
                            </div>

                            <div class="flex flex-col flex-auto overflow-hidden">
                                <div class="flex flex-row items-center">
                                    <div class="title">
                                        {{ $attribute->name }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row items-center justify-end flex-none gap-2">
                                @if($attribute->type == AttributeType::Color)
                                    @foreach($attribute->options as $option)
                                        <x-ui::hint :hint="$option->name">
                                            <a href="{{ is_null($option->variant) ? null : route('variant', [$category->slug, $product->sku, $option->variant]) }}" class="relative group w-9 h-9 rounded-full {{ is_null($option->variant) ? 'opacity-50 pointer-events-none' : '' }} overflow-hidden" style="background-color: #{{ $option->code }}">
                                                @if($current?->options->contains($option->id))
                                                    <div class="flex flex-col justify-center items-center w-9 h-9 bg-secondary-container/30 text-on-secondary-container">
                                                        <x-icon::eye type="micro" />
                                                    </div>
                                                @endif
                                            </a>
                                        </x-ui::hint>
                                    @endforeach
                                @else
                                    <x-ui::dropdown>
                                        <x-slot name="trigger">
                                            <x-ui::chip
                                                :title="$current?->options->where('attribute_id', $attribute->id)->first()->name"
                                                after="chevron-down"
                                            />
                                        </x-slot>

                                        <x-slot name="content">
                                            @foreach($attribute->options as $option)
                                                <x-ui::dropdown.item :href="is_null($option->variant) ? null : route('variant', [$category->slug, $product->sku, $option->variant])">
                                                    {{ $option->name }}
                                                </x-ui::dropdown.item>
                                            @endforeach
                                        </x-slot>
                                    </x-ui::dropdown>
                                @endif
                            </div>
                        </div>

                    @endforeach
                @endif

                @foreach($product->addons as $addon)
                    <x-ui::list.checkbox
                        :title="$addon->name"
                        :description="$addon->description"
                        x-model="items[{{ $loop->index }}].selected"
                        name="addons[]"
                        :value="$addon->id"
                        :wire:key="$addon->id"
                    >
                        <x-slot name="after">
                            <div class="flex-none font-medium text-accent">
                                + {{ price($addon->price)->formatted() }}
                            </div>
                        </x-slot>
                    </x-ui::list.checkbox>
                @endforeach

            </x-ui::list>

            <div class="flex flex-col items-center justify-center h-12 -mx-6 -mb-3 bg-primary text-on-primary font-medium tracking-wide cursor-pointer">
                Order now!
            </div>
        </x-ui::section>

        <x-ui::section x-data="{ tab: 'description' }">
            <x-ui::tab x-model="tab" class="-mx-6 mb-6">
                <x-ui::tab.item name="description" icon="information-circle" label="Description" />
                <x-ui::tab.item name="attributes" icon="adjustments-horizontal" label="Attributes" />
                <x-ui::tab.item name="reviews" icon="star" label="Reviews" badge="O" disabled />
            </x-ui::tab>

            <div x-show="tab == 'description'" class="markdown">
                {!! str($product->description)->markdown() !!}
            </div>

            <x-ui::list x-show="tab == 'attributes'">
                <x-ui::list.value title="Brand">
                    {{ $product->brand->name }}
                </x-ui::list.value>

                @foreach($product->attributes as $attr)
                    <x-ui::list.value :title="$attr->name">
                        {{ $attr->pivot->value }}
                    </x-ui::list.value>
                @endforeach
            </x-ui::list>
        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
