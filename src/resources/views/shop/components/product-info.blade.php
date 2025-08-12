@props(['product'])

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
        <div class="px-6 py-3">
            <x-ui::title title="Basic information" />
        </div>

        <x-ui::list.value title="SKU">
            {{ $product->sku }}
        </x-ui::list.value>

        <x-ui::list.value title="Brand">
            {{ $product->brand->name }}
        </x-ui::list.value>

        <x-ui::list.value title="Category">
            {{ $product->category->name }}
        </x-ui::list.value>

        @foreach($product->attributes as $group)
            <div class="px-6 py-3">
                <x-ui::title :title="$group->title" :subtitle="$group->description" />
            </div>

            @foreach($group->attributes as $attr)
                <x-ui::list.value :title="$attr->name">
                    {{ $attr->value }}
                </x-ui::list.value>
            @endforeach
        @endforeach
    </x-ui::list>
</x-ui::section>
