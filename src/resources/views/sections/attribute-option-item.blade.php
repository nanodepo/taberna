<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Option;

new class extends Component {
    use HasAlert;

    public string $id;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'string', 'max:16'])]
    public string $code = '';

    public function mount(Option $option): void
    {
        $this->id = $option->id;
        $this->name = $option->name;
        $this->code = $option->code;
    }

    public function submit(): void
    {
        $this->validate();

        Option::query()
            ->where('id', $this->id)
            ->update([
                'name' => $this->name,
                'code' => str($this->code)->slug('')->value(),
            ]);

        $this->alert('Сохранено');
    }
} ?>

<form wire:submit="submit" class="flex flex-col gap-3">
    <x-ui::field label="Название" hint="То что увидит пользователь в характеристике" max="64">
        <x-ui::input wire:model="name" max="64" maxlength="64" placeholder="Кораллово красный" required />
    </x-ui::field>

    <x-ui::field label="Значение" hint="Сокращение написанное латиницей без пробелов и символов (или цвет в формате RGB)" max="16">
        <x-ui::input wire:model="code" max="16" maxlength="16" placeholder="#ff7f50" required />
    </x-ui::field>

    <div class="flex flex-row justify-end">
        <div wire:dirty>
            <x-ui::button type="submit" before="plus">Сохранить</x-ui::button>
        </div>
    </div>
</form>
