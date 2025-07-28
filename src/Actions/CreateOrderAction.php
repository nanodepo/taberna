<?php

namespace NanoDepo\Taberna\Actions;

use NanoDepo\Taberna\Data\OrderData;
use NanoDepo\Taberna\Data\OrderItemData;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Events\OrderCreatedEvent;
use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\OrderItem;

class CreateOrderAction
{
    public function handle(OrderData $data): Order
    {
        $order = $this->createOrder($data);
        $this->addItems($order, $data);

        event(new OrderCreatedEvent($order));

        return $order;
    }

    private function createOrder(OrderData $data): Order
    {
        return Order::query()->create([
            'user_id' => $data->user->id,
            'price' => $this->calculatePrice($data),
            'status' => OrderStatus::Pending,
            'shipping_address' => $data->shipping_address,
            'payment_method' => $data->payment_method,
            'comment' => $data->comment,
        ]);
    }

    private function calculatePrice(OrderData $data): int
    {
        $price = 0;

        foreach ($data->items as $item) {
            $itemPrice = is_null($item->variant) ? ($item->product->price - $item->product->discount) : ($item->variant->price - ($item->variant->discount ?? $item->product->discount));
            $itemPrice = $itemPrice * $item->quantity;
            if (is_iterable($item->addons)) {
                $itemPrice = $itemPrice + $item->addons->sum('price');
            }
            $price += $itemPrice;
        }

        return $price;
    }

    private function addItems(Order $order, OrderData $data): void
    {
        foreach ($data->items as $item) {
            $orderItem = OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'variant_id' => $item->variant?->id,
                'quantity' => $item->quantity,
                'price_at_purchase' => $item->variant ? $item->variant->price : $item->product->price,
                'discount_at_purchase' => $item->variant ? $item->variant->discount : $item->product->discount,
                'product_name_at_purchase' => $item->product->name,
                'variant_details_at_purchase' => $item->variant?->options->toArray() ?? [],
                'sku_at_purchase' => $item->variant ? $item->variant->sku : $item->product->sku,
                'is_active' => true,
            ]);

            $this->addAddons($orderItem, $item);
        }
    }

    private function addAddons(OrderItem $item, OrderItemData $data): void
    {
        if (!is_null($data->addons)) {
            foreach ($data->addons as $addon) {
                $item->addons()->attach($addon->id, [
                    'addon_name_at_purchase' => $addon->name,
                    'price_at_purchase' => $addon->price,
                    'quantity' => 1,
                ]);
            }
        }
    }
}
