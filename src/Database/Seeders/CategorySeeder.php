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
                'name' => 'Лабораторное оборудование',
                'slug' => str('Лабораторное оборудование')->slug()->value(),
            ])->create(),
            function(Category $category) {
                tap(
                    CategoryFactory::new()->extra([
                        'category_id' => $category->id,
                        'name' => 'Микроскопы',
                        'slug' => str('Микроскопы')->slug()->value(),
                    ])->create(),
                    function(Category $category) {
                        CategoryFactory::new()->extra([
                            'category_id' => $category->id,
                            'name' => 'Бинокулярные микроскопы',
                            'slug' => str('Бинокулярные микроскопы')->slug()->value(),
                            'is_virtual' => true,
                        ])->create();

                        CategoryFactory::new()->extra([
                            'category_id' => $category->id,
                            'name' => 'Тринокулярные микроскопы',
                            'slug' => str('Тринокулярные микроскопы')->slug()->value(),
                            'is_virtual' => true,
                        ])->create();
                    }
                );

                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Центрифуги',
                    'slug' => str('Центрифуги')->slug()->value(),
                ])->create();

                CategoryFactory::new()->extra([
                    'category_id' => $category->id,
                    'name' => 'Спектрофотометры',
                    'slug' => str('Спектрофотометры')->slug()->value(),
                ])->create();
            }
        );

        CategoryFactory::new()->extra([
            'name' => 'Медицинская техника',
            'slug' => str('Медицинская техника')->slug()->value(),
        ])->create();

        CategoryFactory::new()->extra([
            'name' => 'Аптечная тара',
            'slug' => str('Аптечная тара')->slug()->value(),
        ])->create();
    }
}
