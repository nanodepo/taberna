<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Group;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Attribute;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Group $group;

    public ?string $attrId = null;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';

    #[Validate(['required', 'string', 'max:16'])]
    public string $code = '';

    #[Validate(['required', 'string', 'max:64'])]
    public string $type = 'input';

    #[Validate(['required', 'bool'])]
    public bool $is_variant_defining = false;

    #[Validate(['required', 'bool'])]
    public bool $is_filterable = false;

    #[Validate(['required', 'bool'])]
    public bool $is_required = false;

    public string $option = '';

    public function updatedOpened(bool $val): void
    {
        if (!$val) {
            $this->reset('attrId', 'name', 'type', 'is_required');
        }
    }

    public function add(): void
    {
        $this->open();
    }

    public function edit(Attribute $attr): void
    {
        $this->attrId = $attr->id;
        $this->name = $attr->name;
        $this->code = $attr->code;
        $this->type = $attr->type->value;
        $this->is_variant_defining = $attr->is_variant_defining;
        $this->is_filterable = $attr->is_filterable;
        $this->is_required = $attr->is_required;
        $this->open();
    }

    public function addOption(): void
    {
        $this->validateOnly('option');

        Option::query()->create([
            'attribute_id' => $this->attrId,
            'name' => $this->option,
            'code' => str($this->option)->slug(),
        ]);

        $this->reset('option');
        $this->alert('Вариант добавлен');
    }

    public function deleteOption(Option $option): void
    {
        $option->delete();

        $this->alert('Вариант удален');
    }

    public function submit(): void
    {
        $this->validate(); // REFACTORING

        if (is_null($this->attrId)) {
            Attribute::create([
                'group_id' => $this->groupId,
                'name' => $this->name,
                'type' => AttributeType::from($this->type),
                'is_required' => $this->is_required,
            ]);
        } else {
            Attribute::where('id', $this->attrId)->update([
                'name' => $this->name,
                'type' => AttributeType::from($this->type),
                'is_required' => $this->is_required,
            ]);
        }

        $this->reset('groupId', 'attrId', 'name', 'type', 'is_required');
        $this->alert('Сохранено');
        $this->close();
    }

    public function with(): array
    {
        return [
            'options' => Option::query()->where('attribute_id', $this->attrId)->get(),
        ];
    }
} ?>

<div class="flex flex-col gap-3">
    <div class="flex flex-row justify-between">
        <x-ui::title :title="$group->title" :subtitle="$group->description" />
        <div class="flex flex-row gap-3">
            <x-ui::circle x-on:click="$dispatch('edit-group', { group: '{{ $group->id }}' })" icon="pencil" color="secondary" />
            <x-ui::circle wire:click="add" icon="plus" variant="filled" />
        </div>
    </div>

    <x-ui::list>
        @foreach($group->attributes as $attr)
            <x-ui::list.double
                wire:click="edit('{{ $attr->id }}')"
                before="minus"
                :title="$attr->name"
                :subtitle="$attr->type->title()"
                after="pencil"
            />
        @endforeach
    </x-ui::list>

    <x-ui::dialog wire:model.live="opened">
        <x-slot name="header">
            <x-ui::title title="Добавление" />
            <x-ui::circle icon="x-mark" x-on:click="close" />
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col gap-3 mb-3" x-trap="modal">

                <x-ui::field wire:model="name" label="Название" hint="Например: Объем оперативной памяти" max="64">
                    <x-ui::input x-model="field" max="64" maxlength="64" />
                </x-ui::field>

                <x-ui::divider />

                <div class="flex flex-row items-center justify-between">
                    <x-ui::title title="Обязательный" subtitle="Заполнение этой характеристики является обязательным" />
                    <x-ui::input.switch wire:model="is_required" />
                </div>

                @if(!is_null($attrId))
                    <x-ui::divider />

                    <x-ui::field label="Тип поля">
                        <x-ui::input.select wire:model.live="type">
                            @foreach(AttributeType::cases() as $case)
                                <x-ui::input.select.item :value="$case->value">{{ $case->title() }}</x-ui::input.select.item>
                            @endforeach
                        </x-ui::input.select>
                    </x-ui::field>

                    @if($this->type != AttributeType::Input->value)
                        <x-ui::divider />

                        <x-ui::field label="Вариант" :hint="AttributeType::from($this->type)->hint()">
                            <div class="flex flex-row items-center gap-3">
                                <x-ui::input wire:model="option" x-on:keyup.enter="$wire.addOption" />
                                <x-ui::circle wire:click="addOption" icon="plus" variant="filled" />
                            </div>
                        </x-ui::field>

                        <x-ui::list>
                            @foreach($options as $option)
                                <x-ui::list.value :title="$option->name" :description="$option->code">
                                    <x-ui::circle wire:click="deleteOption('{{ $option->id }}')" icon="trash" color="destructive" />
                                </x-ui::list.value>
                            @endforeach
                        </x-ui::list>
                    @endif
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-ui::button x-on:click="close" color="secondary" variant="text">Отмена</x-ui::button>
            <x-ui::button wire:click="submit">Сохранить</x-ui::button>
        </x-slot>
    </x-ui::dialog>
</div>
