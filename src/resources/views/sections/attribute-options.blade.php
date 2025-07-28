<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Attribute;
use NanoDepo\Taberna\Models\Option;

new class extends Component {
    use HasAlert;

    public string $id;
    public AttributeType $type;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:16'])]
    public string $code = '';

    public function mount(Attribute $attribute): void
    {
        $this->init($attribute);
    }

    #[On('attribute-updated')]
    public function init(Attribute $attribute): void
    {
        $this->id = $attribute->id;
        $this->type = $attribute->type;
    }

    #[Computed]
    public function disabled(): bool
    {
        return $this->type == AttributeType::Input;
    }

    public function submit(): void
    {
        $this->validate();

        Option::query()->create([
            'attribute_id' => $this->id,
            'name' => $this->name,
            'code' => str($this->code)->slug('')->value(),
        ]);

        $this->alert('Вариант добавлен');
        $this->reset('name', 'code');
    }

    public function delete(Option $option): void
    {
        $option->variants()->detach();
        $option->delete();
        $this->alert('Опция удалена');
    }

    public function with(): array
    {
        return [
            'options' => Option::query()->where('attribute_id', $this->id)->get(),
        ];
    }
} ?>

<div class="flex flex-col gap-3">
    <x-ui::section title="Варианты" :hint="$type->description()">
        @if($this->disabled)
            <x-ui::empty text="Для этого типа атрибута нельзя добавить варианты" />
        @else
            <form wire:submit="submit" class="flex flex-col gap-3">
                <x-ui::field label="Название" hint="То что увидит пользователь в характеристике" max="64">
                    <x-ui::input wire:model="name" max="64" maxlength="64" placeholder="Кораллово красный" required />
                </x-ui::field>

                <x-ui::field label="Значение" hint="Сокращение написанное латиницей без пробелов и символов (или цвет в формате RGB)" max="16">
                    <x-ui::input wire:model="code" max="16" maxlength="16" placeholder="#ff7f50" required />
                </x-ui::field>

                <div class="flex flex-row justify-end">
                    <x-ui::button type="submit" before="plus">Добавить</x-ui::button>
                </div>
            </form>
        @endif
    </x-ui::section>

    @if(!$this->disabled)
        @foreach($options as $option)
            <x-ui::section>
                <div class="flex flex-row justify-between mb-3">
                    <x-ui::title title="Вариант {{ $loop->iteration }}" />

                    <x-ui::circle wire:click="delete('{{ $option->id }}')" icon="trash" color="destructive" />
                </div>

                @livewire('sections.attribute-option-item', ['option' => $option], key($option->id))
            </x-ui::section>
        @endforeach
    @endif
</div>

