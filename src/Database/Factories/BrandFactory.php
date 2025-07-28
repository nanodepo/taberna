<?php

namespace NanoDepo\Taberna\Database\Factories;


use NanoDepo\Taberna\Models\Brand;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company;
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }

    public function create(): Brand
    {
        return Brand::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
