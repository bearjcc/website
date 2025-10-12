<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Ursa Minor') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oswald:200,300,400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Styles via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-[color:var(--ink)] bg-[color:var(--space-900)]">
    {{-- Starfield canvas will be inserted here by starfield.js --}}

    {{-- Main content wrapper with stacking context above starfield --}}
    <div id="um-app" class="relative z-10">
        {{-- Sticky top navigation --}}
        <header class="sticky top-0 z-50 backdrop-blur-md bg-[color:var(--space-900)]/80 border-b border-[color:var(--border)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <x-ui.nav-logo />
                    @include('partials.nav')
                </div>
            </div>
        </header>

        {{-- Main content area --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div>

    @livewireScripts
</body>
</html>

