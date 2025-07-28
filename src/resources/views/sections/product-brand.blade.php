<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Product;

new class extends Component {
    use HasAlert;

    public string $id;

    #[Validate(['required', 'ulid'])]
    public string $brand;

    public function mount(Product $subject): void
    {
        $this->id = $subject->id;
        $this->brand = $subject->brand_id;
    }

    public function submit(): void
    {
        $this->validate();

        Product::query()->where('id', $this->id)->update([
            'brand_id' => $this->brand,
        ]);

        $this->alert('Сохранено');
    }

    public function with(): array
    {
        return [
            'brands' => Brand::query()->select('id', 'name')->orderBy('name')->get(),
        ];
    }
} ?>

<x-ui::section header="Бренд" hint="Возможно сначала вам нужно будет добавить компанию">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field label="Компания производитель">
            <x-ui::input.select wire:model="brand">
                @foreach($brands as $brand)
                    <x-ui::input.select.item :value="$brand->id">{{ $brand->name }}</x-ui::input.select.item>
                @endforeach
            </x-ui::input.select>
        </x-ui::field>

        <div class="flex flex-row justify-end">
            <x-ui::button wire:target="brand" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
