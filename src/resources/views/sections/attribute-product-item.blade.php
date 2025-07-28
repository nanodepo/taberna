<?php

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Attribute;
use NanoDepo\Taberna\Models\Option;

new class extends Component {
    use HasAlert;
    use WithModal;

    public string $productId;
    public string $attributeId;
    public string $attributeName;
    public AttributeType $type;
    public bool $required;

    public ?string $value = null;
    public ?string $option_id = null;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:16'])]
    public string $code = '';

    public function mount(): void
    {
        $attr = Attribute::find($this->attributeId);
        $this->attributeName = $attr->name;
        $this->type = $attr->type;
        $this->required = $attr->is_required;

        $rel = DB::table('attribute_product')
            ->where('attribute_id', $this->attributeId)
            ->where('product_id', $this->productId)
            ->first();

        $this->value = $rel->value ?? null;
        $this->option_id = $rel->option_id ?? null;
    }

    public function updatedOptionId($val): void
    {
        $this->value = Option::find($val)?->name;
    }

    public function add(): void
    {
        $this->open();
    }

    public function submit(): void
    {
        $this->validate();

        Option::query()->create([
            'attribute_id' => $this->attributeId,
            'product_id' => $this->productId,
            'name' => $this->name,
            'code' => str($this->code)->slug('')->value(),
        ]);

        $this->alert('Сохранено');
        $this->reset('name', 'code');
        $this->close();
    }

    public function save(): void
    {
        DB::table('attribute_product')
            ->updateOrInsert([
                'attribute_id' => $this->attributeId,
                'product_id' => $this->productId,
            ], [
                'value' => $this->formattingValue(),
                'option_id' => $this->option_id,
            ]);

        $this->alert('Сохранено');
    }

    private function formattingValue(): string
    {
        if ($this->type == AttributeType::Checkbox) {
            return Option::query()->whereIn('code', str($this->value)->chopStart(',')->explode(','))->pluck('name')->join(', ');
        }

        return $this->value;
    }

    public function with(): array
    {
        return [
            'options' => $this->type == AttributeType::Input
                ? []
                : Option::query()
                    ->where('attribute_id', $this->attributeId)
                    ->where(function (Builder $query) {
                        $query->where('product_id', $this->productId)->orWhere('product_id', null);
                    })
                    ->get(),
        ];
    }
} ?>

<div class="flex flex-col px-6 py-3 gap-3">
    <div class="flex flex-row justify-between">
        <x-ui::title :title="$attributeName" :subtitle="$required ? 'Заполнение обязательно' : null" />

        @if($type != AttributeType::Input)
            <x-ui::button wire:click="add" before="plus" variant="outlined">Добавить</x-ui::button>
        @endif
    </div>

    <div class="flex flex-col">
        @if($type == AttributeType::Select)
            <x-ui::input.select wire:model="option_id">
                <x-ui::input.select.item value="">- - - Выбрать - - -</x-ui::input.select.item>
                @foreach($options as $option)
                    <x-ui::input.select.item :value="$option->id">{{ $option->name }}</x-ui::input.select.item>
                @endforeach
            </x-ui::input.select>
        @elseif($type == AttributeType::Checkbox)
            <div
                x-data="{ list: [], result: '' }"
                x-init="
                    list = @js(explode(',', $value));
                    $watch('list', (val) => result = val.toString());
                "
                x-modelable="result"
                wire:model="value"
                class="flex flex-col gap-3"
            >
                @foreach($options as $option)
                    <label class="flex flex-row items-center gap-3">
                        <x-ui::input.checkbox x-model="list" :value="$option->code" />
                        <div class="">{{ $option->name }}</div>
                    </label>
                @endforeach
            </div>
        @elseif($type == AttributeType::Color)
            <div x-data="{ color: null }" x-modelable="color" wire:model="option_id" class="flex flex-row gap-3">
                @foreach($options as $option)
                    <div x-data="{ self: @js($option->id) }" x-bind:class="{ 'rounded-full border border-4': color == self }" style="border-color: {{ str($option->code)->start('#')->append(99)->value() }}">
                        <div class="border-4 border-section rounded-full">
                            <div
                                x-on:click="color = self"
                                x-bind:class="{ 'w-10 h-10 rounded-full': color == self, 'w-12 h-12 rounded': color != self }"
                                style="background-color: {{ str($option->code)->start('#')->value() }}"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui::input wire:model="value" :required="$required" />
        @endif
    </div>

    <div class="flex flex-row justify-end">
        <div wire:dirty>
            <x-ui::button wire:click="save" before="check">Сохранить</x-ui::button>
        </div>
    </div>

    <x-ui::dialog wire:model.live="opened">
        <x-slot name="header">
            <x-ui::title
                title="Добавление опции"
                subtitle="Эта опция будет доступна только для этого товара"
            />

            <x-ui::circle x-on:click="close" icon="x-mark" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit="submit" class="flex flex-col gap-3 mt-3">
                <x-ui::field wire:model="name" label="Название" hint="То что увидит пользователь в характеристике" max="64">
                    <x-ui::input x-model="field" max="64" maxlength="64" placeholder="Кораллово красный" required />
                </x-ui::field>

                <x-ui::field wire:model="code" label="Значение/Идентификатор" hint="Уникальный идентификатор, написан латиницей без пробелов и символов (или цвет в формате RGB)" max="16">
                    <x-ui::input x-model="field" max="16" maxlength="16" placeholder="#ff7f50" required />
                </x-ui::field>

                <div class="flex flex-row justify-between">
                    <x-ui::button x-on:click="close" variant="text" color="secondary">Отмена</x-ui::button>
                    <x-ui::button>Создать</x-ui::button>
                </div>
            </form>
        </x-slot>
    </x-ui::dialog>
</div>
