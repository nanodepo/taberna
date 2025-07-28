<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Feature;
use NanoDepo\Taberna\Models\Group;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Attribute;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Category $category;

    public ?string $attrId = null;
    public AttributeType $type = AttributeType::Input;

    #[Validate(['nullable', 'string', 'max:64'])]
    public string $value = '';

    #[Validate(['required', 'bool'])]
    public bool $required = false;

    public function updatedOpened(bool $val): void
    {
        if (!$val) {
            $this->reset('attrId', 'type', 'value', 'required');
        }
    }

    public function attach(Attribute $attribute): void
    {
        $this->attrId = $attribute->id;
        $this->type = $attribute->type;
        $this->open();
    }

    public function detach(Attribute $attribute): void
    {
        $this->category->attributes()->detach($attribute->id);
    }

    public function submit(): void
    {
        $this->validate();

        $data = $this->type == AttributeType::Input
            ? ['default_value' => $this->value]
            : [
                'default_value' => Option::find($this->value)?->name,
                'option_id' => $this->value
            ];

        $data['is_required'] = $this->required;

        $this->category->attributes()->attach($this->attrId, $data);

        // TODO: Сделать attach/detach для всех товаров в категории (лучше в событии)

        $this->reset('attrId', 'type', 'value', 'required');
        $this->alert('Сохранено');
        $this->close();
    }

    public function with(): array
    {
        return [
            'features' => Feature::query()->where('category_id', $this->category->id)->get(),
            'groups' => Group::query()->with('attributes')->get(),
            'options' => is_null($this->attrId) ? [] : Option::query()->where('attribute_id', $this->attrId)->get(),
        ];
    }
} ?>

<x-ui::layout.single>
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
            <x-ui::breadcrumbs.item :href="route('category.show', $category->id)">{{ $category->name }}</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Характеристики</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        @forelse($groups as $group)
            <x-ui::section>
                <div class="flex flex-row justify-between mb-3">
                    <x-ui::title :title="$group->title" :subtitle="$group->description" />
                </div>

                <x-ui::list>
                    @foreach($group->attributes as $attr)
                        <x-ui::list.value
                            :title="$attr->name"
                            :subtitle="$features->where('attribute_id', $attr->id)->first()?->default_value"
                        >
                            @if($features->where('attribute_id', $attr->id)->first())
                                <x-ui::chip wire:click="detach('{{ $attr->id }}')" before="x-mark" title="Отменить" color="destructive" active />
                            @else
                                <x-ui::chip wire:click="attach('{{ $attr->id }}')" title="Выбрать" />
                            @endif
                        </x-ui::list.value>
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @empty
            <x-ui::section>
                <div class="text-sm text-subtitle text-center mt-3">
                    В сервисе пока еще нет характеристик. <br>
                    В начале вам нужно их добавить и настроить <br>
                    в соответствующем разделе.
                </div>

                <div class="flex flex-row justify-center my-3">
                    <x-ui::button :href="route('group.index')" after="arrow-long-right">Перейти</x-ui::button>
                </div>
            </x-ui::section>
        @endforelse

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title title="Активация" subtitle="Настройка характеристики для категории" />
                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-col gap-3" x-trap="modal">

                    @if($type == AttributeType::Input)
                        <x-ui::field
                            wire:model="value"
                            label="Значение по умолчанию"
                            hint="Вы можете ввести значение, которое будет использоваться по умолчанию для всех товаров"
                            max="64"
                        >
                            <x-ui::input x-model="field" max="64" maxlength="64" required />
                        </x-ui::field>
                    @else
                        <x-ui::field
                            wire:model="value"
                            label="Значение по умолчанию"
                            hint="Вы можете выбрать вариант, который будет использоваться по умолчанию для всех товаров"
                        >
                            <x-ui::input.select x-model="field">
                                <x-ui::input.select.item value="">- - - Выбрать - - -</x-ui::input.select.item>
                                @foreach($options as $option)
                                    <x-ui::input.select.item :value="$option->id">{{ $option->name }}</x-ui::input.select.item>
                                @endforeach
                            </x-ui::input.select>
                        </x-ui::field>
                    @endif

                    <x-ui::list>
                        <x-ui::list.value title="Обязательно к заполнению?">
                            <x-ui::input.switch wire:model="required" />
                        </x-ui::list.value>
                    </x-ui::list>

                </div>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" color="secondary" variant="text">Отмена</x-ui::button>
                <x-ui::button wire:click="submit">Сохранить</x-ui::button>
            </x-slot>
        </x-ui::dialog>
    </x-slot>
</x-ui::layout.single>
