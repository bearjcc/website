<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? $attributes->get('title') ?? 'Lore - Ursa Minor' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="starfield">
    <canvas id="starfield-canvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;"></canvas>
    
    <nav class="sticky top-0 z-50">
        <div class="max-w-[900px] mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex gap-6 items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-star-yellow">Ursa Minor</a>
                    <span class="text-sm text-star-yellow/70">Contributor Area</span>
                </div>
                
                <div class="flex gap-6 items-center">
                    <a href="{{ route('lore.index') }}" class="hover:text-star-yellow transition">Lore</a>
                    <a href="{{ route('home') }}" class="hover:text-star-yellow transition">Public Site</a>
                    <livewire:auth.logout />
                </div>
            </div>
        </div>
    </nav>
    
    <main class="max-w-[900px] mx-auto px-4 py-8">
        {{ $slot }}
    </main>
    
    @livewireScripts
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>

