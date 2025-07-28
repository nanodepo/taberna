<?php

namespace NanoDepo\Taberna\Database\Factories;

use NanoDepo\Taberna\Models\Group;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => str(fake()->word)->ucfirst(),
            'description' => fake()->realTextBetween(),
        ];
    }

    public function create(): Group
    {
        return Group::query()->create($this->data);
    }

//    public function toDto(): UserData
//    {
//        return UserData::from($this->data);
//    }
}
