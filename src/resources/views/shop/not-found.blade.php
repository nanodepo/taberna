<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => settings()->dark])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'NanoDepo') }}</title>

    <!-- Theme -->
    <x-ui::theme :color="settings()->color" />
    <script>
        window.drawer = @js(settings()->drawer);
        window.primaryColor = @js(settings()->color);
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body>

<x-ui::background x-cloak />

<div x-data="container" x-ref="container" class="relative flex flex-col flex-auto overflow-hidden">
    <x-ui::layout.single>

        <x-slot name="content">

            <x-ui::section>
                // 404
            </x-ui::section>

        </x-slot>

    </x-ui::layout.single>
</div>

<div x-cloak x-data class="flex flex-col flex-none text-xs text-center text-hint">
    <div class="flex flex-row items-center justify-center p-1 gap-1">
        <div>Made by <a href="https://t.me/BernhardWilson" class="link">Bernhard Wilson</a> with</div>
        <x-icon::heart type="micro" class="w-4 h-4 animate-heart" />
        <div>and coffee.</div>
    </div>
</div>

@livewire('alert')
@livewireScriptConfig

</body>
</html>
