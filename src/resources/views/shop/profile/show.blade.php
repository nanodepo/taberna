<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Models\Order;

new class extends Component {
    public function logout(): void
    {
        Auth::logout();
        session()->regenerateToken();
        $this->redirectRoute('home');
    }

    public function with(): array
    {
        return [
            'user' => auth()->user(),
            'orders' => Order::query()
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
                ->get()
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="left">
        <x-ui::section>

            <div class="flex flex-col items-center justify-center w-48 h-48 mx-auto">
                <x-ui::avatar :url="thumbnail($user->avatar, '360x360', 'profile')" class="w-36 h-36" />
            </div>

            <div class="absolute top-4 right-4">
                <x-ui::circle wire:click="logout" icon="arrow-left-start-on-rectangle" variant="text" color="destructive" />
            </div>

            <x-ui::list>
                <x-ui::list.icon
                    icon="user"
                    :title="$user->name"
                    subtitle="Ім'я користувача"
                />

                <x-ui::list.icon
                    icon="at-symbol"
                    :title="$user->email"
                    subtitle="Пошта"
                />

                <x-ui::list.value
                    icon="shopping-cart"
                    title="Замовлень"
                >
                    {{ $orders->count() }}
                </x-ui::list.value>

                <x-ui::list.button
                    icon="pencil"
                    title="Редагувати профіль"
                    :href="route('profile.edit')"
                />
            </x-ui::list>

        </x-ui::section>
    </x-slot>

    <x-slot name="right">
        @foreach($orders as $order)
            @livewire('sections.order-public-info', ['order' => $order], key($order->id))
        @endforeach
    </x-slot>
</x-ui::layout.double>
