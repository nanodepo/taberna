<?php

namespace NanoDepo\Taberna\Database\Seeders;

use Illuminate\Database\Seeder;
use NanoDepo\Taberna\Database\Factories\ProductFactory;
use NanoDepo\Taberna\Database\Factories\VariantFactory;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->where('slug', 'mikroskopy')->first();
        $brand = Brand::query()->where('slug', 'micromed')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Бинокулярный микроскоп',
            'description' => 'Биологический бинокулярный микроскоп исследовательского класса, для морфологических исследований в проходящем свете в светлом поле. Применяется в лабораториях медицинских и научно-исследовательских учреждений для проведения длительных рутинных и общеклинических иследований.',
            'price' => 15900,
            'discount' => 0,
            'quantity' => 5,
        ])->create();

        $category = Category::query()->where('slug', 'aptecnaia-tara')->first();
        $brand = Brand::query()->where('slug', 'abracadabra-inc')->first();

        $product = ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Пробирки стеклянные',
            'description' => 'Медицинское стекло, разные размеры',
            'has_variants' => true,
        ])->create();

        (new VariantFactory)->product($product)->times(3);
    }
}
