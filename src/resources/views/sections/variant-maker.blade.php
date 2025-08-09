<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Product $product;

    #[Validate(['required', 'string', 'max:36'])]
    public string $sku;
    #[Validate(['required', 'integer', 'min:1'])]
    public int $price;
    public array $selected = [];

    public int $allOptions = 0;

    public function mount(): void
    {
        $attrs = $this->product->category->attributes()->where('is_variant_defining', true)->get();
        $attrs->load('options');
        $attrs->each(function (\NanoDepo\Taberna\Models\Attribute $attr) {
            $count = $attr->options->where('product_id', $this->product->id)->isEmpty()
                ? $attr->options->where('product_id', null)->count()
                : $attr->options->where('product_id', $this->product->id)->count();

            if ($this->allOptions === 0) {
                $this->allOptions = $this->allOptions + $count;
            } else {
                $this->allOptions = $this->allOptions * $count;
            }

            $this->selected[$attr->code] = '';
        });

        $this->sku = $this->product->sku;
        $this->price = $this->product->price;
    }

    #[On('add-variant')]
    public function init(): void
    {
        $this->open();
    }

    #[Computed]
    public function error(): ?string
    {
        foreach ($this->selected as $item) {
            if (empty($item)) {
                return 'Все варианты обязательны к заполнению';
            }
        }

        $vars = Variant::query()
            ->with('options')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(function (Variant $variant) {
                $variant->ro = $variant->options->pluck('id')->toArray();
                return $variant;
            });

        $exist = false;
        foreach ($vars as $item) {
            if ($item->ro == array_values($this->selected)) {
                $exist = true;
            }
        }

        if ($exist) {
            return 'Такой вариант товара уже есть';
        }

        return null;
    }

    public function submit(): void
    {
        $variant = Variant::query()->create([
            'product_id' => $this->product->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'quantity' => 100,
        ]);

        $variant->options()->attach($this->selected);

        $this->close();
//        $this->reset('selected');
        $this->alert('Вариант добавлен');
        $this->dispatch('variant-created');
    }

    public function with(): array
    {
        return [
            'attributes' => $this->product->category->attributes()->where('is_variant_defining', true)->get(),
            'variants' => $this->opened ? Variant::query()->with('options')->where('product_id', $this->product->id)->get() : collect(),
        ];
    }
} ?>

<div>
    <x-ui::dialog wire:model.live="opened">
        <x-slot name="header">
            <x-ui::title
                title="Добавление варианта"
                subtitle="Выберите характеристики которые будут соответствовать этому варианту товара"
            />

            <x-ui::circle x-on:click="close" icon="x-mark" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit="submit" class="flex flex-col gap-3">
                @foreach($attributes as $attribute)
                    <x-ui::list>
                        <x-ui::list.value :title="$attribute->name">
                            <div x-data="{ selected: null }" x-modelable="selected" wire:model.live="selected.{{ $attribute->code }}">
                                <x-ui::dropdown>
                                    <x-slot name="trigger">
                                        @if(empty($selected[$attribute->code]))
                                            <x-ui::chip title="Выбрать" />
                                        @else
                                            <x-ui::chip before="check" :title="$attribute->options->where('id', $selected[$attribute->code])->first()?->name" active />
                                        @endif
                                    </x-slot>

                                    <x-slot name="content">
                                        @foreach($attribute->options->where('product_id', $product->id)->isEmpty() ? $attribute->options->where('product_id', null) : $attribute->options->where('product_id', $product->id) as $option)
                                            @php
                                                $count_option = $variants->where(fn ($item) =>  $item->options->where('product_id', $product->id)->isEmpty() ? $item->options->where('product_id', null)->contains($option) : $item->options->where('product_id', $product->id)->contains($option))->count();
                                            @endphp

                                            <x-ui::dropdown.item
                                                x-on:click="selected = '{{ $option->id }}'"
                                                :disabled="count($selected) > 1 && ($allOptions / ($attribute->options->where('product_id', $product->id)->isEmpty() ? $attribute->options->where('product_id', null)->count() : $attribute->options->where('product_id', $product->id)->count())) <= $count_option"
                                            >
                                                {{ $option->name }}
                                            </x-ui::dropdown.item>
                                        @endforeach
                                    </x-slot>
                                </x-ui::dropdown>
                            </div>
                        </x-ui::list.value>
                    </x-ui::list>
                @endforeach

                <div class="text-sm text-destructive">{{ $this->error }}</div>

                <x-ui::field wire:model="sku" label="SKU" max="36">
                    <x-ui::input x-model="field" max="36" maxlength="36" />
                </x-ui::field>

                <x-ui::field wire:model="price" label="Цена">
                    <x-ui::input x-model="field" type="number" min="1" />
                </x-ui::field>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-ui::button x-on:click="close" variant="text" color="secondary">Отмена</x-ui::button>
            <x-ui::button wire:click="submit" :disabled="!is_null($this->error)">Создать</x-ui::button>
        </x-slot>
    </x-ui::dialog>
</div>
