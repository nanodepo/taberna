<?php

namespace NanoDepo\Taberna\Database\Seeders;

use Illuminate\Database\Seeder;
use NanoDepo\Taberna\Database\Factories\CategoryFactory;
use NanoDepo\Taberna\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        tap(
            CategoryFactory::new()->extra([
                'name' => 'Aetheric Weaving',
                'slug' => str('Aetheric Weaving')->slug()->value(),
            ])->create(),
            function(Category $category) {
                tap(
                    CategoryFactory::new()->extra([
                        'category_id' => $category->id,
                        'name' => 'Lumina Textiles',
                        'slug' => str('Lumina Textiles')->slug()->value(),
                    ])->create(),
                    function(Category $category) {
                        CategoryFactory::new()->extra([
                            'category_id' => $category->id,
                            'name' => 'Stardust Silks',
                            'slug' => str('Stardust Silks')->slug()->value(),
                            'is_virtual' => true,
                        ])->create();

                        CategoryFactory::new()->extra([
                            'category_id' => $category->id,
                            'name' => 'Echo-Weave Fabrics',
                            'slug' => str('Echo-Weave Fabrics')->slug()->value(),
                            'is_virtual' => true,
                        ])->create();
                    }
                );

                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Celestial Armory',
                    'slug' => str('Celestial Armory')->slug()->value(),
                ])->create();

                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Ephemeral Adornments',
                    'slug' => str('Ephemeral Adornments')->slug()->value(),
                ])->create();
            }
        );

        CategoryFactory::new()->extra([
            'name' => 'Geode Gardens',
            'slug' => str('Geode Gardens')->slug()->value(),
        ])->create();

        CategoryFactory::new()->extra([
            'name' => 'Alchemist\'s Pantry',
            'slug' => str('Alchemist\'s Pantry')->slug()->value(),
        ])->create();

        tap(
            CategoryFactory::new()->extra([
                'name' => 'Mechanica Anima',
                'slug' => str('Mechanica Anima')->slug()->value(),
            ])->create(),
            function(Category $category) {
                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Clockwork Critters',
                    'slug' => str('Clockwork Critters')->slug()->value(),
                    'is_virtual' => true,
                ])->create();

                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Astro-Navigation Tools',
                    'slug' => str('Astro-Navigation Tools')->slug()->value(),
                    'is_virtual' => true,
                ])->create();
            }
        );
    }
}
