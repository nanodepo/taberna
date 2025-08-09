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
                'title' => 'Dimensions',
                'description' => 'Main parameters of the product',
            ])
            ->create();

        $other = GroupFactory::new()
            ->extra([
                'title' => 'Other',
                'description' => 'Parameters that do not fit other sections',
            ])
            ->create();

        AttributeFactory::new()
            ->extra([
                'group_id' => $general->id,
                'name' => 'Height',
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
                'name' => 'Width',
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
                'name' => 'Weight',
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
                    'name' => 'Color',
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
                    'name' => 'Silver Blue',
                    'code' => '8a9edc',
                ])->create();

                OptionFactory::new()->extra([
                    'attribute_id' => $attribute->id,
                    'name' => 'Soft Moon',
                    'code' => 'f5d69d',
                ])->create();

                OptionFactory::new()->extra([
                    'attribute_id' => $attribute->id,
                    'name' => 'Ocean',
                    'code' => '8ec7d2',
                ])->create();
            }
        );
    }
}
