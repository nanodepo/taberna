<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public Order $order;

    public function with(): array
    {
        return [];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('profile.show')">Profile</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('profile.order.index')">Orders history</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Order</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.order-public-info', ['order' => $order])
    </x-slot>

    <x-slot name="right">
        @foreach($order->items as $item)
            <x-ui::section title="Product #{{ $loop->iteration }}">
                <x-ui::list>
                    <x-ui::list.double
                        before="cube"
                        :title="$item->product_name_at_purchase"
                        after="arrow-long-right"
                        :href="$item->variant ? route('variant', [$item->product->category->slug, $item->product->sku, $item->variant->sku]) : route('product', [$item->product->category->slug, $item->product->sku])"
                    />

                    <x-ui::list.value
                        icon="square-3-stack-3d"
                        title="Quantity"
                    >
                        {{ $item->quantity }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="banknotes"
                        title="Price"
                        accent
                    >
                        {{ price($item->price_at_purchase)->formatted() }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="receipt-percent"
                        title="Discount"
                    >
                        {{ price($item->discount_at_purchase)->formatted() }}
                    </x-ui::list.value>

                </x-ui::list>

                @if($item->addons->isNotEmpty())
                    <x-ui::title title="Addons" class="my-3" />

                    <x-ui::list>
                        @foreach($item->addons as $addon)
                            <x-ui::list.value
                                icon="squares-plus"
                                :title="$addon->pivot->addon_name_at_purchase"
                                accent
                            >
                                {{ price($addon->pivot->price_at_purchase)->formatted() }}
                            </x-ui::list.value>
                        @endforeach
                    </x-ui::list>
                @endif
            </x-ui::section>
        @endforeach
    </x-slot>
</x-ui::layout.double>
