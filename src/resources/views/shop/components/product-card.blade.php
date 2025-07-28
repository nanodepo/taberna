@props(['product'])

<div class="flex flex-col w-1/2 lg:w-1/3 xl:w-1/4 p-3">
    <a href="{{ $product->link }}" class="relative group flex flex-col cursor-pointer">
        <div class="w-full aspect-4/5 bg-surface rounded-lg bg-center bg-no-repeat bg-contain group-hover:scale-104 transition" style="background-image: url('{{ $product->image?->thumbnail() ?? asset('images/500.svg') }}')"></div>
        <div class="mt-2 text-subtitle">{{ $product->name }}</div>
        <div class="flex flex-row gap-3">
            @if($product->discount > 0)
                <div class="mt-1 text-sm line-through text-destructive">{{ price($product->price) }}</div>
            @endif
            <div class="mt-1 text-sm font-medium">{{ price($product->price - $product->discount)->formatted() }}</div>
        </div>

        <div class="flex flex-row absolute top-2 right-2 gap-1">
            @if(file_exists(public_path('objects/'.str($product->sku)->lower()->value().'-web.glb')))
                <div class="flex flex-row items-center gap-1 px-2 py-1 line text-sm text-primary leading-none rounded-full border border-primary">
                    <x-icon::cube type="micro" />
                    <div class="text-xs font-bold">3D</div>
                </div>
            @endif

            @if($product->discount > 0)
                <div class="flex flex-row items-center gap-1 px-2 py-1 line text-sm text-secondary leading-none rounded-full border border-secondary">
                    <x-icon::receipt-percent type="micro" />
                    <div class="text-xs font-bold">{{ intval(100 / $product->price * $product->discount) }}%</div>
                </div>
            @endif
        </div>
    </a>
</div>
