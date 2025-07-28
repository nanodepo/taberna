<?php

namespace NanoDepo\Taberna\Database\Factories;

use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

class VariantFactory extends Factory
{
    public Product $product;

    public function definition(): array
    {
        return [
            'product_id' => $this->product->id,
            'sku' => str($this->product->sku)->append(fake()->numberBetween(1, 20))->value(),
            'price' => fake()->numberBetween(100, 1000),
            'discount' => fake()->boolean(20) ? fake()->numberBetween(5, 30) : 0,
            'quantity' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    public function create(): Variant
    {
        return Variant::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }

    public function product(Product $product): self
    {
        $clone = clone $this;
        $clone->product = $product;
        return $clone->extra();
    }
}
