<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public Order $order;
} ?>

<x-ui::section x-data="{ opened: false }">
    <div class="flex flex-row justify-between items-center">
        <x-ui::title
            title="Order №{{ $order->created_at->format('ymdHis') }}"
            :subtitle="$order->status->name"
        />

        <x-ui::circle x-on:click="opened = !opened" icon="chevron-left" x-bind:class="{ '-rotate-90': opened }" />
    </div>

    <div class="my-3">
        <x-ui::steps>
            <x-ui::steps.item
                :title="OrderStatus::Pending->name"
                :active="$order->status == OrderStatus::Pending"
                :completed="$order->status == OrderStatus::Completed || $order->status == OrderStatus::Sent || $order->status == OrderStatus::Processing"
                first
            />

            <x-ui::steps.item
                :title="OrderStatus::Processing->name"
                :active="$order->status == OrderStatus::Processing"
                :completed="$order->status == OrderStatus::Completed || $order->status == OrderStatus::Sent"
            />

            <x-ui::steps.item
                :title="OrderStatus::Sent->name"
                :active="$order->status == OrderStatus::Sent"
                :completed="$order->status == OrderStatus::Completed"
            />

            <x-ui::steps.item
                :title="OrderStatus::Completed->name"
                :completed="$order->status == OrderStatus::Completed"
                last
            />
        </x-ui::steps>
    </div>

    <x-ui::list x-collapse x-show="opened">
        <x-ui::list.double
            before="hashtag"
            :title="$order->created_at->format('ymdHis')"
            subtitle="UID"
            after="square-2-stack"
            x-clipboard.raw="{{ $order->created_at->format('ymdHis') }}"
        />

        <x-ui::list.value
            icon="bolt"
            title="Status"
        >
            {{ $order->status->name }}
        </x-ui::list.value>

        <x-ui::list.value
            icon="banknotes"
            title="Sum"
            accent
        >
            {{ price($order->price)->formatted() }}
        </x-ui::list.value>

        <x-ui::list.value
            icon="calendar-days"
            title="Date"
        >
            {{ $order->created_at->isoFormat('D MMMM YYYY') }}
        </x-ui::list.value>

        <x-ui::list.value
            icon="map-pin"
            title="Address"
        >
            <div class="max-w-48">
                {{ $order->shipping_address }}
            </div>
        </x-ui::list.value>

        @if($order->comment)
            <x-ui::list.icon
                icon="chat-bubble-bottom-center-text"
                title="Comment"
                :subtitle="$order->comment"
                :truncate="false"
            />
        @endif
    </x-ui::list>
</x-ui::section>
