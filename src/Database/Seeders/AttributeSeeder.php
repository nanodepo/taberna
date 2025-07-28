<?php

namespace NanoDepo\Taberna\Database\Seeders;

use Illuminate\Database\Seeder;
use NanoDepo\Taberna\Database\Factories\AttributeFactory;
use NanoDepo\Taberna\Database\Factories\GroupFactory;
use NanoDepo\Taberna\Database\Factories\OptionFactory;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $general = GroupFactory::new()
            ->extra([
                'title' => 'Параметры',
                'description' => 'Основные характеристики товара',
            ])
            ->create();

        $other = GroupFactory::new()
            ->extra([
                'title' => 'Другое',
                'description' => 'Параметры которые не подходят к другим разделам',
            ])
            ->create();

        AttributeFactory::new()
            ->extra([
                'group_id' => $general->id,
                'name' => 'Высота',
                'code' => 'weight',
                'type' => AttributeType::Input->value,
                'is_variant_defining' => false,
                'is_filterable' => false,
                'is_required' => true,
            ])
            ->create();

        AttributeFactory::new()
            ->extra([
                'group_id' => $general->id,
                'name' => 'Ширина',
                'code' => 'height',
                'type' => AttributeType::Input->value,
                'is_variant_defining' => false,
                'is_filterable' => false,
                'is_required' => true,
            ])
            ->create();

        AttributeFactory::new()
            ->extra([
                'group_id' => $general->id,
                'name' => 'Вес',
                'code' => 'weight',
                'type' => AttributeType::Input->value,
                'is_variant_defining' => false,
                'is_filterable' => false,
                'is_required' => false,
            ])
            ->create();

        tap(
            AttributeFactory::new()
                ->extra([
                    'group_id' => $other->id,
                    'name' => 'Цвет',
                    'code' => 'color',
                    'type' => AttributeType::Color->value,
                    'is_variant_defining' => true,
                    'is_filterable' => true,
                    'is_required' => true,
                ])
                ->create(),
            function (Attribute $attribute) {
                OptionFactory::new()->extra([
                    'attribute_id' => $attribute->id,
                    'name' => 'Pink Red',
                    'code' => 'ff5252',
                ])->create();

                OptionFactory::new()->extra([
                    'attribute_id' => $attribute->id,
                    'name' => 'Cold Blue',
                    'code' => '0088cc',
                ])->create();
            }
        );
    }
}
