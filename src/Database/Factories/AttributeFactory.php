<?php

namespace NanoDepo\Taberna\Database\Factories;

use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Attribute;
use NanoDepo\Taberna\Models\Group;

class AttributeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_id' => Group::query()->inRandomOrder()->first()->id,
            'name' => str(fake()->text)->ucfirst()->value(),
            'code' => str(fake()->word)->slug()->value(),
            'type' => fake()->randomElement(AttributeType::cases()),
            'is_variant_defining' => fake()->boolean(),
            'is_filterable' => fake()->boolean(),
            'is_required' => fake()->boolean(),
        ];
    }

    public function create(): Attribute
    {
        return Attribute::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
