<?php

use App\Domains\User\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'users' => User::all(),
        ];
    }
} ?>

<x-ui::layout>
    <x-slot name="content">
        <x-ui::section title="Users">
            <x-ui::list>
                @foreach($users as $user)
                    <x-ui::list.icon
                        icon="user-circle"
                        :subhead="$user->role->name"
                        :title="$user->name"
                        :subtitle="$user->phone"
                        :description="$user->email"
                    />
                @endforeach
            </x-ui::list>
        </x-ui::section>
    </x-slot>
</x-ui::layout>
