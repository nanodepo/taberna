<?php

use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Group;

new class extends Component {
    use HasAlert;
    use WithModal;

    public ?string $groupId = null;

    #[Validate(['required', 'string', 'max:64'])]
    public string $title = '';
    #[Validate(['required', 'string', 'max:250'])]
    public string $description = '';

    #[On('edit-group')]
    public function edit(Group $group): void
    {
        $this->groupId = $group->id;
        $this->title = $group->title;
        $this->description = $group->description;
        $this->open();
    }

    public function add(): void
    {
        $this->open();
    }

    public function updatedOpened($val): void
    {
        if (!$val) {
            $this->reset();
        }
    }

    public function makeAttr(string $group): void
    {
        $attr = \NanoDepo\Taberna\Models\Attribute::query()->create([
            'group_id' => $group,
            'name' => 'Новая характеристика',
            'code' => 'new-attr',
        ]);

        $this->redirectRoute('attribute.show', $attr->id);
    }

    public function submit(): void
    {
        $data = $this->validate();

        if (is_null($this->groupId)) {
            Group::query()->create($data);
        } else {
            Group::query()->where('id', $this->groupId)->update($data);
        }

        $this->close();
        $this->reset();
        $this->alert('Сохранено');
    }

    public function with(): array
    {
        return [
            'groups' => Group::all(),
        ];
    }
} ?>

<x-ui::layout.single>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>Характеристики</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="content">
        <x-ui::section title="Характеристики">
            <div class=" text-sm text-subtitle">
                Характеристики работают очень просто:
            </div>

            <div class="mt-3 text-sm text-subtitle">
                1. Добавляете группу; <br>
                2. Добавляете характеристику; <br>
                3. Настраиваете эту характеристику; <br>
                4. Выбираете её в настройках категории и указываете "значение по умолчанию"; <br>
                5. Эта характеристика будет применена ко всем товарам в категории.
            </div>

            <div class="flex flex-row justify-end mt-3">
                <x-ui::button wire:click="add" before="folder-plus" color="secondary">Добавить группу</x-ui::button>
            </div>
        </x-ui::section>

        @foreach($groups as $group)
            <x-ui::section>
                <div class="flex flex-row justify-between mb-3">
                    <x-ui::title :title="$group->title" :subtitle="$group->description" />
                    <div class="flex flex-row gap-3">
                        <x-ui::circle wire:click="edit('{{ $group->id }}')" icon="pencil" color="secondary" />
                    </div>
                </div>

                <x-ui::list>
                    <x-ui::list.button
                        icon="plus-circle"
                        title="Добавить характеристику"
                        wire:click="makeAttr('{{ $group->id }}')"
                    />

                    @foreach($group->attributes as $attr)
                        <x-ui::list.double
                            :href="route('attribute.show', $attr->id)"
                            before="minus"
                            :title="$attr->name"
                            :subtitle="$attr->type->title()"
                            after="arrow-long-right"
                        />
                    @endforeach
                </x-ui::list>
            </x-ui::section>
        @endforeach

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title title="Добавление" />
                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-col gap-3" x-trap="modal">
                    <x-ui::field wire:model="title" label="Название" hint="Используйте простые короткие названия" max="64">
                        <x-ui::input x-model="field" max="64" maxlength="64" required />
                    </x-ui::field>

                    <x-ui::field wire:model="description" label="Описание" hint="Пара слов о категории" max="250">
                        <x-ui::input.textarea x-model="field" max="250" maxlength="250" required />
                    </x-ui::field>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" color="secondary" variant="text">Отмена</x-ui::button>
                <x-ui::button wire:click="submit">Сохранить</x-ui::button>
            </x-slot>
        </x-ui::dialog>
    </x-slot>
</x-ui::layout.single>
