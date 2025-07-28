<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use HasAlert;

    public string $id;
    public string $type;

    #[Validate(['required', 'string', 'max:36'])]
    public string $sku;
    #[Validate(['required', 'integer', 'min:1'])]
    public int $price;
    #[Validate(['required', 'integer', 'min:0'])]
    public int $discount;
    #[Validate(['required', 'integer', 'min:0'])]
    public int $quantity;

    public function mount(Product|Variant $subject): void
    {
        $this->id = $subject->id;
        $this->type = $subject::class;

        $this->sku = $subject->sku;
        $this->price = $subject->price;
        $this->discount = $subject->discount;
        $this->quantity = $subject->quantity;
    }

    public function submit(): void
    {
        $data = $this->validate();

        if ($this->type == Product::class) {
            $subject = Product::find($this->id);
        } else {
            $subject = Variant::find($this->id);
        }

        $subject->fill($data);
        $subject->save();

        $this->alert('Сохранено');
    }
} ?>

<x-ui::section header="Настройки" hint="Это очень важные поля, заполняйте их внимательно">
    <form wire:submit="submit" class="flex flex-col gap-3">

        <div class="flex flex-row gap-3">
            <x-ui::field label="SKU" hint="Код товара" class="overflow-hidden">
                <x-ui::input wire:model="sku" />
            </x-ui::field>

            <x-ui::field label="Количество" hint="Количество товаров на складе" class="overflow-hidden">
                <x-ui::input wire:model="quantity" type="number" />
            </x-ui::field>
        </div>

        <div class="flex flex-row gap-3">
            <x-ui::field label="Цена" hint="Без учета скидки" class="overflow-hidden">
                <x-ui::input wire:model="price" type="number" />
            </x-ui::field>

            <x-ui::field label="Скидка" class="overflow-hidden">
                <x-ui::input wire:model="discount" type="number" />
            </x-ui::field>
        </div>

        <div class="flex flex-row justify-end">
            <x-ui::button wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
