<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use HasAlert;

    public string $productId;

    public array $selected = [];

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
        $this->selected = $product->addons()->pluck('addons.id')->toArray();
    }

    public function submit(): void
    {
        $product = Product::find($this->productId);

        $product->addons()->sync($this->selected);

        $this->alert('Сохранено');
    }

    public function with(): array
    {
        return [
            'addons' => Addon::all(),
        ];
    }
} ?>

<x-ui::section title="Дополнения" hint="Вы можете выбрать любое количество дополнений к товару">
    <x-ui::list>
        @forelse($addons as $addon)
            <x-ui::list.checkbox
                :title="$addon->name"
                :description="$addon->description"
                :value="$addon->id"
                wire:model="selected"
            >
                <x-slot name="after">
                    <div class="text-lg font-medium text-accent">{{ price($addon->price)->formatted() }}</div>
                </x-slot>
            </x-ui::list.checkbox>
        @empty
            <x-ui::empty />
        @endforelse
    </x-ui::list>

    <div class="flex flex-row justify-end mt-3">
        <x-ui::button wire:click="submit" wire:dirty>Сохранить</x-ui::button>
    </div>
</x-ui::section>
