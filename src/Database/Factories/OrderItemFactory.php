<?php

namespace NanoDepo\Taberna\Database\Factories;

use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\OrderItem;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::query()->inRandomOrder()->first()->id,
            'product_id' => Product::query()->inRandomOrder()->first()->id,
            'variant_id' => Variant::query()->inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1, 5),
            'price' => fake()->numberBetween(100, 1000),
            'discount' => fake()->boolean ? fake()->numberBetween(10, 100) : 0,
            'is_active' => fake()->boolean(80),
        ];
    }

    public function create(): OrderItem
    {
        return OrderItem::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
