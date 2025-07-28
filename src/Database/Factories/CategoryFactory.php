<?php

namespace NanoDepo\Taberna\Database\Factories;


use NanoDepo\Taberna\Models\Category;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->realText(32);
        return [
            'name' => str($name)->ucfirst()->value(),
            'slug' => str($name)->slug()->value(),
            'title' => fake()->realText(64),
            'description' => fake()->realTextBetween(1000, 2000),
            'icon' => fake()->randomElement(['cube', 'bolt', 'fire', 'camera', 'cloud', 'gift']),
            'is_active' => true,
            'is_virtual' => false,
        ];
    }

    public function create(): Category
    {
        return Category::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
