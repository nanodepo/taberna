<?php

namespace NanoDepo\Taberna\Database\Factories;


use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::query()->inRandomOrder()->first()->id,
            'brand_id' => Brand::query()->inRandomOrder()->first()->id,
            'sku' => str(fake()->postcode)->prepend('X')->value(),
            'name' => str(fake()->text)->ucfirst(),
            'intro' => fake()->realTextBetween(150, 250),
            'description' => fake()->realTextBetween(500, 1000),
            'price' => fake()->numberBetween(100, 1000),
            'discount' => fake()->boolean(30) ? fake()->numberBetween(0, 99) : 0,
            'quantity' => fake()->numberBetween(1, 10),
            'is_active' => true,
            'has_variants' => false,
            'is_main' => false,
        ];
    }

    public function create(): Product
    {
        return Product::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
