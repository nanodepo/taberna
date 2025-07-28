<?php

use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Group;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use WithModal;

    public Product $product;

    public function with(): array
    {
        return [
            'groups' => Group::query()->get(),
            'features' => $this->product->category->attributes,
            'attributes' => $this->product->attributes,
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.index')">Категории</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.show', $product->category->id)">{{ $product->category->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('product.show', $product->id)">{{ $product->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Редактирование</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.product-name', ['subject' => $product])

        @livewire('sections.image-uploader', ['subject' => $product])

        @livewire('sections.product-brand', ['subject' => $product])

        @livewire('sections.product-settings', ['subject' => $product])

        @livewire('sections.seo-manager', ['subject' => $product])
    </x-slot>

    <x-slot name="right">
        @foreach($groups as $group)
            @if($features->where('group_id', $group->id)->isNotEmpty())
                <x-ui::section :header="$group->title" :hint="$group->description">
                    <x-ui::list class="-my-3">
                        @foreach($features->where('group_id', $group->id) as $attr)
                            @livewire(
                                'sections.attribute-product-item',
                                ['productId' => $product->id, 'attributeId' => $attr->id],
                                key($attr->id)
                            )
                        @endforeach
                    </x-ui::list>
                </x-ui::section>
            @endif
        @endforeach
    </x-slot>
</x-ui::layout.double>
