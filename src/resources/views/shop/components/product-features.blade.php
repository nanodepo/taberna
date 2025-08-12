@use(NanoDepo\Taberna\Enums\AttributeType)

@props(['product'])

<x-ui::list>
    <x-ui::list.value icon="banknotes" title="Price" accent>
        <span class="text-lg font-bold">{{ price($this->calculatePrice)->formatted() }}</span>
    </x-ui::list.value>

    <x-ui::list.value icon="square-3-stack-3d" title="Quantity">
        <x-ui::input.counter wire:model.live="quantity" :min="1" :max="$product->quantity" />
    </x-ui::list.value>

    @if($product->has_variants)
        @foreach($product->variants as $attribute)
            <div class="group item items-center">
                <div class="flex flex-col justify-center items-center flex-none w-6 h-6">
                    <x-icon::squares-2x2 />
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
                                <a href="{{ is_null($option->variant) ? null : route('variant', [$product->category->slug, $product->sku, $option->variant]) }}" class="relative group w-9 h-9 rounded-full {{ is_null($option->variant) ? 'opacity-50 pointer-events-none' : '' }} overflow-hidden" style="background-color: #{{ $option->code }}">
                                    @if($option->active)
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
                                    :title="$attribute->options->where('active', true)->first()?->name ?? 'Select'"
                                    after="chevron-down"
                                />
                            </x-slot>

                            <x-slot name="content">
                                @foreach($attribute->options as $option)
                                    <x-ui::dropdown.item :href="is_null($option->variant) ? null : route('variant', [$product->category->slug, $product->sku, $option->variant])">
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
            wire:model.live="addons"
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
