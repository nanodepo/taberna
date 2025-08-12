@props(['product'])

<div
    x-data="{ imageUrl: '' }"
    x-init="imageUrl = '{{ $product->image?->thumbnail() ?? asset('images/500.svg') }}'"
    class="flex flex-col"
>
    <div class="flex flex-col justify-center items-center -mx-6 -mt-3 mb-3">
        <div class="w-full aspect-square bg-center bg-cover" x-bind:style="{ backgroundImage: 'url(' + imageUrl + ')' }"></div>
    </div>

    @if($product->images->count() > 1)
        <div class="flex flex-row flex-wrap justify-center gap-1 mb-6">
            @foreach($product->images as $image)
                <div x-on:click="imageUrl = '{{ $image }}'" class="relative w-12 h-12 rounded-lg border border-section-separator bg-hint bg-cover bg-center cursor-pointer overflow-hidden" style="background-image: url('{{ $image }}')">
                    <div x-show="imageUrl === '{{ $image }}'" class="absolute inset-0 flex flex-col justify-center items-center bg-secondary-container/50 text-on-secondary-container">
                        <x-icon::eye type="solid" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
