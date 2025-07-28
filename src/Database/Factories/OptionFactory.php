<?php

namespace NanoDepo\Taberna\Database\Factories;


use NanoDepo\Taberna\Models\Attribute;
use NanoDepo\Taberna\Models\Option;

class OptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attribute_id' => Attribute::query()->inRandomOrder()->first()->id,
            'product_id' => null,
            'name' => str(fake()->text)->ucfirst()->value(),
            'code' => str(fake()->word)->slug()->value(),
        ];
    }

    public function create(): Option
    {
        return Option::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
