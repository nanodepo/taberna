<?= '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('categories') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('about-us') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ route('delivery') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ route('return-policy') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    @foreach ($categories as $category)
        <url>
            <loc>{{ route('category', $category->slug) }}</loc>
            @if ($category->updated_at)
                <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>

        @foreach ($category->products as $product)
            <url>
                <loc>{{ route('product', [$category->slug, $product->sku]) }}</loc>
                <lastmod>{{ $product->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
            @if($product->has_variants && $product->variants->isNotEmpty())
                @foreach($product->variants as $variant)
                    <url>
                        <loc>{{ route('variant', [$category->slug, $product->sku, $variant->sku]) }}</loc>
                        <lastmod>{{ $variant->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                        <changefreq>weekly</changefreq>
                        <priority>0.7</priority>
                    </url>
                @endforeach
            @endif
        @endforeach
    @endforeach

</urlset>
