<?php

namespace NanoDepo\Taberna\Data;

use App\Domains\User\Models\User;
use Illuminate\Support\Collection;
use NanoDepo\Nexus\ValueObjects\Price;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;
use Spatie\LaravelData\Data;

class ProductPriceData extends Data
{
    public function __construct(
        public Price $amount,
        public Price $base,
        public Price $discount,
        public bool $has_sale,
        public int $percentage,
    ) {}
}
