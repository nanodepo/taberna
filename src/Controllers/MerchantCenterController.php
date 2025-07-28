<?php

namespace NanoDepo\Taberna\Controllers;

use Illuminate\Http\Response;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

class MerchantCenterController
{
    public function __invoke(): Response
    {
        return response()->view('taberna::merchant-center', [
            'products' => Product::query()
                ->with(['category:id,slug', 'variants.options'])
                ->where('is_active', true)
                ->get(),
        ])->header('Content-Type', 'text/xml');
    }
}
