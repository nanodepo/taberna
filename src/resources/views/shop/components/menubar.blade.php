<x-ui::nav.bar>
    <x-ui::nav.bar.link
        icon="home"
        label="Home"
        :href="route('home')"
        :active="request()->routeIs('home')"
        wire:navigate
    />

    <x-ui::nav.bar.link
        icon="cube"
        label="Products"
        :href="route('categories')"
        :active="request()->routeIs(['categories', 'category'])"
        wire:navigate
        badge
    />

    <x-ui::nav.bar.link
        icon="information-circle"
        label="About us"
        :href="route('about-us')"
        :active="request()->routeIs('about-us')"
        wire:navigate
    />

    <x-ui::nav.bar.link
        icon="shopping-bag"
        label="Basket"
        :href="route('basket')"
        :active="request()->routeIs('basket')"
        :badge="session()->has('basket') ? count(session()->get('basket')) : null"
        wire:navigate
    />
</x-ui::nav.bar>
