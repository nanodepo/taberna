<x-ui::nav.panel x-data x-cloak class="-mt-3 -mx-3 mb-3">
    <x-slot name="left">
        <x-logo text="Taberna" :href="route('home')" icon="nanodepo" />
    </x-slot>

    <x-slot name="right" class="gap-3">
        @livewire('switch-mode')

        @if(auth()->check())
            <a href="{{ route('profile.show') }}">
                <x-ui::avatar :url="thumbnail(auth()->user()->avatar, '96x96', 'profile')" class="w-9 h-9" />
            </a>
        @else
            <x-ui::circle :href="route('auth.login')" icon="arrow-right-end-on-rectangle" />
        @endif
    </x-slot>
</x-ui::nav.panel>
