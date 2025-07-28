<?php

namespace NanoDepo\Taberna\Database\Factories;

use App\Domains\User\Models\User;
use NanoDepo\Taberna\Models\Order;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'price' => fake()->numberBetween(100, 1000),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'shipping_address' => fake()->address(),
            'payment_method' => fake()->randomElement(['paypal', 'stripe', 'razor']),
        ];
    }

    public function create(): Order
    {
        return Order::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
