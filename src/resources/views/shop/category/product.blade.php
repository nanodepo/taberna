<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Data\OrderData;
use NanoDepo\Taberna\Data\ProductData;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;
use NanoDepo\Taberna\Supports\AttributesManager;

new
#[Layout('layouts.shop')]
class extends Component {
    public Category $category;
    public Product $product;
    public ?Variant $variant = null;

    public int $price = 0;
    public int $quantity = 1;
    public array $addons = [];

    public function mount(): void
    {
        $product = $this->getProduct();
        // dd($product->attributes);
        $this->price = $product->price->amount->value();
    }

    #[Computed]
    public function calculatePrice(): int
    {
        $addons = Addon::query()->whereIn('id', $this->addons)->get();

        return ($this->price + $addons->sum('price')) * $this->quantity;
    }

    public function getProduct(): ProductData
    {
        return new AttributesManager($this->product, $this->variant)->handle();
    }

    public function add(): void
    {
        $product = $this->getProduct();

        $color = fake()->hexColor;
        $item = [
            'id' => $color,
            'product' => $product->id,
            'variant' => $product->vid,
            'quantity' => $this->quantity,
            'addons' => count($this->addons) > 0 ? $this->addons : null,
        ];

        session()->push('basket.'.$color, $item);

        $this->redirectRoute('basket');
    }

    public function with(): array
    {
        return [
            'current' => $this->getProduct(),
        ];
    }
} ?>

<x-ui::layout.single>

    <x-slot name="content">

        <x-ui::section class="mt-0 sm:mt-3">

            <x-taberna::navbar />

            <x-ui::breadcrumbs class="mb-6">
                <x-ui::breadcrumbs.item :href="route('home')" wire:navigate>Home</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('categories')" wire:navigate>Categories</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item :href="route('category', $category->slug)" wire:navigate>{{ $category->name }}</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
                <x-ui::breadcrumbs.item active>{{ $current->name }}</x-ui::breadcrumbs.item>
            </x-ui::breadcrumbs>

            <x-taberna::product-image :product="$current" />

            <x-ui::header :title="$current->name" :subtitle="$current->sku" class="mb-6" />

            <x-taberna::product-features :product="$current" />

            <x-taberna::main-button type="button" wire:click="add">
                Order now!
            </x-taberna::main-button>
        </x-ui::section>

        <x-taberna::product-info :product="$current" />

    </x-slot>

</x-ui::layout.single>
