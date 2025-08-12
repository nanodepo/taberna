@props(['order'])

<x-ui::list.value
    icon="cube"
    :subhead="$order->status->name"
    title="Заказ №{{ $order->created_at->format('ymdHis') }} ({{ $order->created_at->locale('ru')->isoFormat('DD MMMM') }})"
    :subtitle="$order->user->name"
    :href="route('order.show', $order->id)"
>
    {{ price($order->price)->formatted() }}
</x-ui::list.value>
