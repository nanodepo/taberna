<?php

use App\Domains\User\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\HasImageWriter;

new class extends Component {
    use HasAlert;
    use HasImageWriter;
    use WithFileUploads;

    public User $user;

    #[Validate(['nullable', 'image', 'max:5000'])]
    public ?TemporaryUploadedFile $avatar = null;

    #[Validate(['required', 'string', 'max:64'])]
    public string $name = '';
    #[Validate(['required', 'email'])]
    public string $email = '';

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function submit(): void
    {
        $data = $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => is_null($this->avatar)
                ? $this->user->avatar
                : $this->writeImage(
                    image: $this->avatar,
                    dir: 'profile',
                    width: 500,
                    height: 500,
                    original: true
                )
        ]);

        $this->reset('avatar');

        $this->alert('Updated');
    }
} ?>

<x-ui::section title="Profile edit">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field label="Photo" hint="In memory of you">
            <input type="file" wire:model="avatar" class="input file" />
            @error('avatar')
                <div class="validation-error">{{ $message }}</div>
            @enderror
        </x-ui::field>

        <x-ui::field wire:model="name" label="Name" hint="Please indicate the first and last name of the recipient of the goods" max="64">
            <x-ui::input
                x-model="field"
                maxlength="64"
                max="64"
                required
            />
        </x-ui::field>

        <x-ui::field wire:model="email" label="Email" max="64">
            <x-ui::input
                type="email"
                x-model="field"
                maxlength="64"
                max="64"
                required
            />
        </x-ui::field>

        <div class="flex fle-row justify-end">
            <x-ui::button type="submit">Save</x-ui::button>
        </div>
    </form>
</x-ui::section>
