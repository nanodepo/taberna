<?php

namespace NanoDepo\Taberna\Controllers;

use Illuminate\Http\Response;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

class SitemapController
{
    public function __invoke(): Response
    {
        return response()->view('taberna::sitemap', [
            'categories' => Category::query()
                ->select(['id', 'slug', 'updated_at'])
                ->with(['products:id,sku,has_variants,is_active,updated_at', 'products.variants:id,product_id,sku,updated_at'])
                ->get(),
        ])->header('Content-Type', 'text/xml');
    }
}
