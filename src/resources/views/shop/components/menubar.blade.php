<x-ui::nav.bar>
    <x-ui::nav.bar.link
        icon="home"
        label="Главная"
        :href="route('home')"
        :active="request()->routeIs('home')"
    />

    <x-ui::nav.bar.link
        icon="cube"
        label="Товары"
        :href="route('categories')"
        :active="request()->routeIs(['categories', 'category'])"
        badge
    />

    <x-ui::nav.bar.link
        icon="information-circle"
        label="О нас"
        :href="route('about-us')"
        :active="request()->routeIs('about-us')"
    />

    <x-ui::nav.bar.link
        icon="shopping-bag"
        label="Корзина"
        :href="route('basket')"
        :active="request()->routeIs('basket')"
    />
</x-ui::nav.bar>
