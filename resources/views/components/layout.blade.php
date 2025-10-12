<!DOCTYPE html>
<html lang="en" data-scroll="0">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $title ?? 'Ursa Minor Games - Where Games Are Born Under the Stars' }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description ?? 'Ursa Minor Games: Browser games, F1 predictions, original board games, and world-building. Join our gaming community and follow our journey towards a board game café in New Zealand.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'browser games, sudoku, chess, F1 predictions, board games, game development, New Zealand, gaming community' }}">
    <meta name="author" content="Ursa Minor Games">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle ?? $title ?? 'Ursa Minor Games - Where Games Are Born Under the Stars' }}">
    <meta property="og:description" content="{{ $ogDescription ?? $description ?? 'Creating memorable gaming experiences from browser games to board games and beyond.' }}">
    <meta property="og:url" content="{{ config('app.url') }}">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{ $head ?? '' }}
</head>

<body>
    <!-- Starfield Background -->
    <div id="stars">
        <div class="circle blink" id="circle-animate"></div>
        <div class="circle" id="circle"></div>
    </div>

    <!-- Header Component -->
    <x-header />

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Scripts -->
    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('scroll.js') }}"></script>
    <script>
        // Initialize starfield on page load
        if (typeof stars === 'function') {
            stars();
        }
    </script>
    
    {{ $scripts ?? '' }}
</body>
</html>

