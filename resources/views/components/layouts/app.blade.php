<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased {{ config('ui.lowercase_mode') ? 'lowercase-mode' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = 'Ursa Minor Games';
        $pageTitle = $title ?? 'Home';
        $fullTitle = $pageTitle === 'Home' 
            ? $siteName 
            : $siteName . ' | ' . $pageTitle;
        if (config('ui.lowercase_mode')) {
            $fullTitle = mb_strtolower($fullTitle);
        }
    @endphp
    <title>{{ $fullTitle }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oswald:200,300,400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Styles via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-ink bg-space-900 min-h-screen flex flex-col">
    {{-- Starfield canvas will be inserted here by starfield.js --}}

    {{-- Main content wrapper with stacking context above starfield --}}
    <div id="um-app" class="relative z-10 flex flex-col min-h-screen">
        {{-- Sticky top navigation with generous spacing --}}
        <header id="um-header" class="sticky top-0 z-50 backdrop-blur-md bg-ink/5 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-6">
                    {{-- Nav logo with morph target --}}
                    <div data-um-morph="nav-logo" class="transition-all duration-200">
                        <x-ui.nav-logo class="h-8" />
                    </div>
                    @include('partials.nav')
                </div>
            </div>
            {{-- Glass gradient border that tapers to transparency --}}
            <div class="absolute bottom-0 inset-x-0 h-[0.25rem] bg-gradient-to-b from-ink/10 to-transparent pointer-events-none" aria-hidden="true"></div>
        </header>

        {{-- Main content area --}}
        <main id="main-content" class="flex-1 flex flex-col">
            <div id="top"></div>
            <div class="flex-1">
                {{ $slot }}
            </div>
        </main>

        {{-- Footer --}}
        <x-ui.horizon-footer />
    </div>

    @livewireScripts
</body>
</html>

