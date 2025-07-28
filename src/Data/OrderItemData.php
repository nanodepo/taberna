<?php

namespace NanoDepo\Taberna\Data;

use Illuminate\Support\Collection;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;
use Spatie\LaravelData\Data;

class OrderItemData extends Data
{
    public function __construct(
        public Product $product,
        public int $quantity,
        public ?Variant $variant = null,
        public ?Collection $addons = null,
    ) {}
}
