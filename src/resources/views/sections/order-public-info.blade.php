<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public Order $order;
} ?>

<x-ui::section title="Замовлення №{{ $order->created_at->format('ymdHis') }}">
    <div class="my-3">
        <x-ui::steps>
            <x-ui::steps.item
                :title="OrderStatus::Pending->title()"
                :active="$order->status == OrderStatus::Pending"
                :completed="$order->status == OrderStatus::Completed || $order->status == OrderStatus::Sent || $order->status == OrderStatus::Processing"
                first
            />

            <x-ui::steps.item
                :title="OrderStatus::Processing->title()"
                :active="$order->status == OrderStatus::Processing"
                :completed="$order->status == OrderStatus::Completed || $order->status == OrderStatus::Sent"
            />

            <x-ui::steps.item
                :title="OrderStatus::Sent->title()"
                :active="$order->status == OrderStatus::Sent"
                :completed="$order->status == OrderStatus::Completed"
            />

            <x-ui::steps.item
                :title="OrderStatus::Completed->title()"
                :completed="$order->status == OrderStatus::Completed"
                last
            />
        </x-ui::steps>
    </div>

    <x-ui::list>
        <x-ui::list.icon
            icon="bolt"
            :title="$order->status->title()"
            subtitle="Статус"
        />

        <x-ui::list.double
            before="hashtag"
            :title="$order->created_at->format('ymdHis')"
            subtitle="Ідентифікатор"
            after="square-2-stack"
            x-clipboard.raw="{{ $order->created_at->format('ymdHis') }}"
        />

        <x-ui::list.value
            icon="banknotes"
            title="Сума"
            subtitle="{{ $order->payment_method == 'post' ? 'Післяплатою' : 'Переказом на картку' }}"
            accent
        >
            {{ price($order->price)->formatted() }}
        </x-ui::list.value>

        <x-ui::list.icon
            icon="calendar-days"
            :title="$order->created_at->locale('uk')->isoFormat('D MMMM YYYY')"
            subtitle="Дата замовлення"
        />

        <x-ui::list.icon
            icon="map-pin"
            :title="$order->shipping_address"
            subtitle="Адреса"
        />

        @if($order->comment)
            <x-ui::list.icon
                icon="chat-bubble-bottom-center-text"
                title="Комментар"
                :subtitle="$order->comment"
                :truncate="false"
            />
        @endif
    </x-ui::list>
</x-ui::section>
