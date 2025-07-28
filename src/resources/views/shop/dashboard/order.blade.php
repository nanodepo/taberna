<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public function with(): array
    {
        return [
            'orders' => Order::query()
                ->orderByDesc('id')
                ->get(),
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Заказы</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @if($orders->where('status', OrderStatus::Pending)->isNotEmpty())
            <x-ui::section title="Новые">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Pending) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif

        @if($orders->where('status', OrderStatus::Processing)->isNotEmpty())
            <x-ui::section title="В работе">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Processing) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif

        @if($orders->where('status', OrderStatus::Sent)->isNotEmpty())
            <x-ui::section title="Отправлены">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Sent) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif

        @if($orders->isEmpty())
            <x-ui::section title="Orders">
                <x-ui::empty text="The list of orders will be displayed here" />
            </x-ui::section>
        @endif
    </x-slot>

    <x-slot name="right">
        @if($orders->where('status', OrderStatus::Completed)->isNotEmpty())
            <x-ui::section title="Выполненные">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Completed) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif

        @if($orders->where('status', OrderStatus::Canceled)->isNotEmpty())
            <x-ui::section title="Отмененные">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Canceled) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif

        @if($orders->where('status', OrderStatus::Failed)->isNotEmpty())
            <x-ui::section title="Не выполненные">
                <x-ui::list>
                    @foreach($orders->where('status', OrderStatus::Failed) as $order)
                        <x-taberna::order-list-item :order="$order" />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endif
    </x-slot>
</x-ui::layout.double>
