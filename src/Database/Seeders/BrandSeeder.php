<?php

namespace NanoDepo\Taberna\Database\Seeders;

use Illuminate\Database\Seeder;
use NanoDepo\Taberna\Database\Factories\BrandFactory;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        BrandFactory::new()->extra([
            'name' => 'NanoDepo',
            'slug' => 'nanodepo',
        ])->create();

        BrandFactory::new()->extra([
            'name' => 'Abracadabra Inc.',
            'slug' => 'abracadabra-inc',
        ])->create();
    }
}
