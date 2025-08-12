<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public function with(): array
    {
        return [
            'orders' => Order::query()
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
                ->get(),
        ];
    }
} ?>

<x-ui::layout.single>
    <x-slot name="content">
        <x-ui::section title="Orders history">
            <x-ui::list>
                @foreach($orders as $order)
                    <x-ui::list.double
                        before="cube"
                        :subhead="$order->status->name"
                        title="Order №{{ $order->created_at->format('ymdHis') }}"
                        subtitle="Products: {{ $order->items()->count() }} | Price: {{ price($order->price)->formatted() }}"
                        after="eye"
                        :href="route('profile.order.show', $order->id)"
                    />
                @endforeach
            </x-ui::list>
        </x-ui::section>
    </x-slot>
</x-ui::layout.single>
