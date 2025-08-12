<?php

namespace NanoDepo\Taberna\Data;

use App\Domains\User\Models\User;
use Illuminate\Support\Collection;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Image;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public string $id,
        public ?string $vid,
        public string $sku,
        public string $name,
        public ?Image $image,
        public string $intro,
        public string $description,
        public ProductPriceData $price,
        public int $quantity,
        public bool $is_active,
        public bool $has_variants,
        public bool $is_main,
        public string $link,
        public Category $category,
        public Brand $brand,
        public Collection $images,
        public Collection $attributes,
        public Collection $addons,
        public Collection $reviews,
        public Collection $variants
    ) {}
}
