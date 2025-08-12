<?php

use App\Domains\User\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use NanoDepo\Nexus\Traits\HasAlert;

new class extends Component {
    use HasAlert;

    public User $user;

    #[Validate(['required', 'string', 'min:6', 'max:24', 'confirmed:confirmation'])]
    public string $password = '';
    public string $confirmation = '';

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function submit(): void
    {
        $this->validate();

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset('password', 'confirmation');

        $this->alert('Updated');
    }
} ?>

<x-ui::section title="Password update">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="password" label="Password" hint="Use a strong password" max="16">
            <x-ui::input
                type="password"
                x-model="field"
                autocomplete="new-password"
                max="16"
                maxlength="16"
            />
        </x-ui::field>

        <x-ui::field wire:model="confirmation" label="Password confirmation" hint="To avoid errors, repeat your password" max="16">
            <x-ui::input
                type="password"
                x-model="field"
                autocomplete="new-password"
                max="16"
                maxlength="16"
            />
        </x-ui::field>

        <div class="flex fle-row justify-end">
            <x-ui::button type="submit">Save</x-ui::button>
        </div>
    </form>
</x-ui::section>
