<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Category $category;

    public array $selected = [];

    public function add(): void
    {
        $this->selected = $this->category->virtual->pluck('id')->toArray();
        $this->open();
    }

    public function updatedOpened($val): void
    {
        if (!$val) {
            $this->reset('selected');
        }
    }

    public function save(): void
    {
        $this->category->virtual()->sync($this->selected);
        $this->reset('selected');
        $this->alert('Сохранено');
        $this->close();
    }

    public function with(): array
    {
        return [
            'categories' => Category::query()
                ->with([
                    'children:id,category_id,slug,name,is_virtual',
                ])
                ->withCount('products')
                ->where('category_id', $this->category->id)
                ->get(),
            'products' => $this->opened ? Product::query()->select('id', 'name')->get() : collect(),
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('category.index')">Категории</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            @if($category->parent)
                <x-ui::breadcrumbs.item :href="route('category.show', $category->parent->id)">{{ $category->parent->name }}</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.divider />
            @endif
            <x-ui::breadcrumbs.item active>{{ $category->name }}</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        @livewire('sections.category-info', ['category' => $category])

        @if(!$category->is_virtual)
            <x-ui::section hint="Вы можете добавить неограниченное количество подкатегорий любой вложенности. Так же используйте виртуальные категории чтобы сделать более гибкое представление товаров.">
                <div class="flex flex-row justify-between items-center mb-3">
                    <x-ui::title
                        title="Дочерние категории"
                        subtitle="Разделяйте товары на категории"
                    />

                    <x-ui::button :href="route('category.children', $category->id)" before="folder-plus">
                        Добавить
                    </x-ui::button>
                </div>

                <x-ui::list>
                    @forelse($categories as $cat)
                        <x-taberna::tree-category-item :category="$cat" />
                    @empty
                        <x-ui::empty />
                    @endforelse
                </x-ui::list>
            </x-ui::section>
        @endif
    </x-slot>

    <x-slot name="right">
        <x-ui::section>
            @if($category->is_virtual)
                <div class="flex flex-row justify-between items-center mb-3">
                    <x-ui::title
                        title="Товары"
                        subtitle="В виртуальную категорию нельзя напрямую добавлять товары, вместо этого вы можете выбрать товары которые в ней будут отображаться"
                    />

                    <x-ui::button wire:click="add" before="squares-plus">Выбрать</x-ui::button>
                </div>
            @else
                <div class="flex flex-row justify-between items-center mb-3">
                    <x-ui::title
                        title="Товары"
                        subtitle="Добавляйте товары и их варианты"
                    />

                    <x-ui::button :href="route('product.create', $category->id)" before="plus">Добавить</x-ui::button>
                </div>
            @endif

            <x-ui::list>
                @forelse($category->is_virtual ? $category->virtual : $category->products as $product)
                    <x-ui::list.item
                        :title="$product->name"
                        :href="route('product.show', $product->id)"
                    >
                        <x-slot name="before">
                            <x-ui::avatar :url="$product->image?->thumbnail(96)" icon="cube" class="w-12 h-12" />
                        </x-slot>

                        <x-slot name="subtitle">
                            <div class="flex flex-row gap-3">
                                <x-ui::meta icon="hashtag" :text="$product->sku" />
                                <x-ui::meta icon="banknotes" :text="$product->price" />
                                <x-ui::meta icon="receipt-percent" :text="$product->discount > 0 ? $product->discount : 'O'" />
                                <x-ui::meta icon="square-3-stack-3d" :text="$product->quantity > 0 ? $product->quantity : 'O'" />
                                @if($product->has_variants && $product->variants->count() > 0)
                                    <x-ui::meta icon="rectangle-stack" :text="$product->variants->count()" />
                                @endif
                            </div>
                        </x-slot>

                        <x-slot name="after" class="text-hint">
                            <x-icon::arrow-long-right />
                        </x-slot>
                    </x-ui::list.item>
                @empty
                    <x-ui::empty />
                @endforelse
            </x-ui::list>
        </x-ui::section>

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title title="Добавление" subtitle="Вы можете выбрать любое количество товаров" />
                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <x-ui::list>
                    @foreach($products as $product)
                        <x-ui::list.checkbox wire:model="selected" :value="$product->id" :title="$product->name" />
                    @endforeach
                </x-ui::list>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" color="secondary" variant="text">Отмена</x-ui::button>
                <x-ui::button wire:click="save">Сохранить</x-ui::button>
            </x-slot>
        </x-ui::dialog>
    </x-slot>
</x-ui::layout.double>
