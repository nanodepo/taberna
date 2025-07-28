<?php

use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    public string $title = 'Схожі товари';

    public function with(): array
    {
        return [
            'products' => Product::query()->inRandomOrder()->limit(4)->get(),
        ];
    }
} ?>

<div class="flex flex-col">
    <div class="p-3 text-lg tracking-wide font-bold text-center text-subtitle">
        {{ $title }}
    </div>

    <div class="flex flex-row flex-wrap snap-x overflow-clip">
        @foreach($products as $product)
            <x-taberna::product-card :product="$product" />
        @endforeach
    </div>

    @push('scripts')
        <script>
            gtag(
                'event',
                'view_item_list',
                {
                    'item_list_name': '{{ $title }}',
                    'items': @js($products->map(fn ($item) => ['item_id' => $item->sku, 'item_name' => $item->name, 'price' => $item->price])->toArray())
                }
            );
        </script>
    @endpush
</div>
