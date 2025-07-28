<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Variant;

new class extends Component {
    use HasAlert;

    public Variant $variant;

    #[Validate([
        'selected' => ['required', 'array'],
        'selected.*' => ['required', 'ulid'],
    ])]
    public array $selected;

    public function mount(): void
    {
        $this->variant->product
            ->attributes()
            ->where('is_variant_defining', true)
            ->get()
            ->each(function ($attr) {
                $this->selected[$attr->code] = null;
            });

        $this->variant->options->each(function ($option) {
            if (array_key_exists($option->attribute->code, $this->selected)) {
                $this->selected[$option->attribute->code] = $option->id;
            }
        });
    }

    public function submit(): void
    {
        $this->validate();

        $this->variant->options()->detach();
        $this->variant->options()->attach($this->selected);

        $this->alert('Сохранено');
    }

    public function with(): array
    {
        return [
            'attributes' => $this->variant->product->attributes()->where('is_variant_defining', true)->get(),
        ];
    }
} ?>

<x-ui::section header="Варианты характеристик">
    <form wire:submit="submit" class="flex flex-col gap-3">
        @foreach($attributes as $attribute)
            <x-ui::field :label="$attribute->name" :required="$attribute->is_required">
                <x-ui::input.select wire:model="selected.{{ $attribute->code }}">
                    <x-ui::input.select.item value="" :disabled="!empty($selected[$attribute->code])">- - - Выбрать - - -</x-ui::input.select.item>
                    @foreach($attribute->options as $option)
                        <x-ui::input.select.item :value="$option->id">{{ $option->name }}</x-ui::input.select.item>
                    @endforeach
                </x-ui::input.select>
            </x-ui::field>
        @endforeach

        <div class="flex flex-row justify-end">
            <x-ui::button wire:click="submit" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
