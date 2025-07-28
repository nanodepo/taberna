<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

new
#[Layout('layouts.shop')]
class extends Component {

    #[\Livewire\Attributes\Url]
    public ?string $search;

    public function with(): array
    {
        return [
            'categories' => Category::query()->whereNull('category_id')->get(),
            'products' => Product::all(),
        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::header
                title="Welcome to Taberna!"
                subtitle="Lorem ipsum dolor sit amet consectetur adipisicing elit."
                class="mt-12 mb-6"
            />

            <form class="relative w-full max-w-md mx-auto mb-6">
                <input
                    type="text"
                    name="search"
                    wire:model="search"
                    placeholder="Search for products..."
                    class="input round w-full"
                />
                <x-ui::circle type="submit" icon="fire" class="absolute" style="top: 3px; right: 3px;" />
            </form>

            <x-taberna::menubar />

        </x-ui::section>


        <div class="flex flex-row gap-3 px-3 py-6 overflow-x-auto scrollbar-none">
            @foreach($categories as $category)
                <a href="{{ route('category', $category->slug) }}" class="flex flex-col cursor-pointer">
                    <x-ui::chip :before="$category->icon" :title="$category->name" />
                </a>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap">
            @foreach($products as $product)
                <div class="flex flex-col w-full sm:w-1/2 p-3 sm:p-1.5">
                    <x-ui::card
                        :image="$product->image?->thumbnail(500) ?? asset('images/500.svg')"
                        :title="$product->name"
                        :subtitle="$product->intro"
                        :href="route('product', [$product->category->slug, $product->sku])"
                    >
                        <x-slot name="footer">
                            <div class="text-xl font-medium text-secondary">{{ price($product->price)->formatted() }}</div>
                            <x-ui::circle icon="shopping-bag" variant="filled" />
                        </x-slot>
                    </x-ui::card>
                </div>
            @endforeach
        </div>

        <x-ui::section class="mt-6">
            <x-ui::header
                title="Lorem ipsum dolor sit amet, consectetur adipisicing"
                subtitle="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae eum minus quae quibusdam sunt tempora ullam? A aliquam amet aperiam aspernatur distinctio ducimus ea earum eligendi enim est, eveniet, ex excepturi explicabo illo iste iure maxime natus nesciunt nostrum odio optio perferendis quam quasi quia quidem quisquam ratione recusandae reiciendis rerum saepe sed sequi suscipit tenetur ullam, vel voluptatem voluptates. Asperiores assumenda cumque distinctio dolore ducimus non, omnis quod velit? Beatae consequuntur distinctio dolor eligendi est eum excepturi id nesciunt, officia, perspiciatis quibusdam reprehenderit similique totam? Aliquam distinctio dolore eaque exercitationem expedita illum, ipsa ipsam officia quibusdam repellendus tempora vitae?"
                class="my-6"
            />
        </x-ui::section>

    </x-slot>

</x-ui::layout.single>
