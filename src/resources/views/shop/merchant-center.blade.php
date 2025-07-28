<?= '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>Продукты {{ config('app.name') }}</title>
        <link>{{ route('home') }}</link>
        <description>Фид товаров {{ config('app.name') }} для Google Merchant Center</description>

        @foreach ($products as $product)
            @if($product->has_variants && $product->variants->isNotEmpty())
                @foreach($product->variants as $variant)
                    <item>
                        <!-- Уникальный идентификатор (SKU) варианта -->
                        <g:id>{{ $variant->sku }}</g:id>
                        <!-- Общая группа товаров (модель «{{ $product->name }}») -->
                        <g:item_group_id>{{ $product->sku }}</g:item_group_id>
                        <!-- Название с указанием варианта (цвет, материал, размер) -->
                        <g:title>{{ $product->prefix }} {{ $product->name }} ({{ $variant->options->pluck('name')->join('/') }})</g:title>
                        <!-- Описание товара со страницы Rud.Red:contentReference[oaicite:24]{index=24} -->
                        <g:description>{{ $product->intro }}</g:description>
                        <g:link>{{ route('variant', [$product->category->slug, $product->sku, $variant->sku]) }}</g:link>
                        @if($variant->images->isNotEmpty())
                            <!-- Ссылка на главное изображение -->
                            <g:image_link>{{ $variant->image->thumbnail() }}</g:image_link>
                        <!-- Ссылка на дополнительные изображения -->
                            @foreach($variant->images as $image)
                                @if($image->id != $variant->image->id)
                                    <g:additional_image_link>{{ $image->thumbnail() }}</g:additional_image_link>
                                @endif
                            @endforeach
                        @else
                            <!-- Ссылка на главное изображение -->
                            <g:image_link>{{ $product->image?->thumbnail() ?? asset('images/500.svg') }}</g:image_link>
                        <!-- Ссылка на дополнительные изображения -->
                            @foreach($product->images as $image)
                                @if($image->id != $product->image->id)
                                    <g:additional_image_link>{{ $image->thumbnail() }}</g:additional_image_link>
                                @endif
                            @endforeach
                        @endif
                        @if(file_exists(public_path('objects/'.str($product->sku)->lower()->value().'-web.glb')))
                            <g:virtual_model_link>{{ asset('objects/'.str($product->sku)->lower()->value().'-web.glb') }}</g:virtual_model_link>
                        @endif
                        <g:condition>new</g:condition>
                        <g:availability>in_stock</g:availability>
                        <g:price>{{ $variant->price }}.00 UAH</g:price>
                        @if($variant->discount > 0 || $product->discount > 0)
                            <g:sale_price>{{ $variant->price - ($variant->discount > 0 ? $variant->discount : $product->discount) }}.00 UAH</g:sale_price>
                        @endif
                        <g:sale_price_effective_date>{{ Illuminate\Support\Carbon::parse('2025-06-16')->toIso8601String() }}/{{ Illuminate\Support\Carbon::parse('2025-07-30')->toIso8601String() }}</g:sale_price_effective_date>
                        <g:shipping>
                            <g:country>UA</g:country>
                        </g:shipping>
                        <g:brand>RudRed</g:brand>
                        <!-- Характеристики варианта -->
                        @foreach($variant->options as $opt)
                            <g:{{ $opt->attribute->code }}>{{ $opt->name }}</g:{{ $opt->attribute->code }}>
                        @endforeach
                    </item>
                @endforeach
            @endif
        @endforeach

    </channel>
</rss>
