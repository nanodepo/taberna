<?php

use Livewire\Attributes\Layout;
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
            <x-ui::breadcrumbs.item :href="route('profile.show')">Особистий кабінет</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('profile.order.index')">Історія замовлень</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Замовлення</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.order-public-info', ['order' => $order])
    </x-slot>

    <x-slot name="right">
        @foreach($order->items as $item)
            <x-ui::section title="Товар #{{ $loop->iteration }}">
                <x-ui::list>
                    <x-ui::list.icon
                        icon="cube"
                        :title="$item->product_name_at_purchase"
                    />

                    <x-ui::list.value
                        icon="square-3-stack-3d"
                        title="Кількість"
                    >
                        {{ $item->quantity }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="banknotes"
                        title="Ціна"
                    >
                        {{ price($item->price_at_purchase)->formatted() }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="receipt-percent"
                        title="Знижка"
                    >
                        {{ price($item->discount_at_purchase)->formatted() }}
                    </x-ui::list.value>

                </x-ui::list>

                @if($item->addons->isNotEmpty())
                    <x-ui::title title="Доповнення" class="my-3" />

                    <x-ui::list>
                        @foreach($item->addons as $addon)
                            <x-ui::list.value
                                icon="squares-plus"
                                :title="$addon->pivot->addon_name_at_purchase"
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
