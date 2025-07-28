<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Enums\AttributeType;

new class extends Component {
    use HasAlert;

    public \NanoDepo\Taberna\Models\Attribute $attribute;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';

    #[Validate(['required', 'string', 'max:16'])]
    public string $code = '';

    #[Validate(['required', 'string', 'max:64'])]
    public string $type = AttributeType::Input->value;

    #[Validate(['required', 'bool'])]
    public bool $is_variant_defining = false;

    #[Validate(['required', 'bool'])]
    public bool $is_filterable = false;

    #[Validate(['required', 'bool'])]
    public bool $is_required = false;

    public function mount(): void
    {
        if ($this->attribute->code != 'new-attr') {
            $this->name = $this->attribute->name;
            $this->code = $this->attribute->code;
        }
        $this->type = $this->attribute->type->value;
        $this->is_variant_defining = $this->attribute->is_variant_defining;
        $this->is_filterable = $this->attribute->is_filterable;
        $this->is_required = $this->attribute->is_required;
    }

    public function updatedType($val): void
    {
        if ($val == AttributeType::Input->value) {
            $this->is_variant_defining = false;
            $this->is_filterable = false;
        }
    }

    public function submit(): void
    {
        $data = $this->validate();
        $data['code'] = str($this->code)->slug('')->value();

        $this->attribute->update($data);

        $this->alert('Сохранено');
        $this->dispatch('attribute-updated', attribute: $this->attribute->id);
    }

    public function delete(): void
    {
        $this->attribute->delete();
        $this->redirectRoute('group.index');
    }
} ?>

<x-ui::section title="Характеристики">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="name" label="Название" hint="Например: Объем оперативной памяти" max="64">
            <x-ui::input x-model="field" max="64" maxlength="64" />
        </x-ui::field>

        <x-ui::field wire:model="code" label="Идентификатор" hint="Сокращение написанное латиницей без пробелов и символов" max="16">
            <x-ui::input x-model="field" max="16" maxlength="16" />
        </x-ui::field>

        <x-ui::field label="Тип поля">
            <x-ui::input.select wire:model.live="type">
                @foreach(AttributeType::cases() as $case)
                    <x-ui::input.select.item :value="$case->value">{{ $case->title() }}</x-ui::input.select.item>
                @endforeach
            </x-ui::input.select>
        </x-ui::field>

        <div class="flex flex-row items-center justify-between">
            <x-ui::title title="Варианты товара" subtitle="Использовать эту характеристику для создания вариантов товара" />
            <x-ui::input.switch wire:model="is_variant_defining" :disabled="$type == AttributeType::Input->value" />
        </div>

        <x-ui::divider />

        <div class="flex flex-row items-center justify-between">
            <x-ui::title title="Фильтры" subtitle="Учитывать эту характеристику для формирования фильтров в каталоге" />
            <x-ui::input.switch wire:model="is_filterable" :disabled="$type == AttributeType::Input->value" />
        </div>

        <x-ui::divider />

        <div class="flex flex-row items-center justify-between">
            <x-ui::title title="Обязательный" subtitle="Заполнение этой характеристики является обязательным" />
            <x-ui::input.switch wire:model="is_required" />
        </div>

        <div class="flex flex-row justify-between">
            <x-ui::button type="button" wire:click="delete" wire:confirm="Вы действительно хотите удалить характеристику?" variant="text" color="destructive">Удалить</x-ui::button>
            <x-ui::button type="submit" wire:dirty.attr.remove="disabled" disabled>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
