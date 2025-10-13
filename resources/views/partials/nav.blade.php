{{-- Simple navigation links for app layout --}}
<nav aria-label="Main navigation" class="flex items-center gap-1">
    <a 
        href="{{ route('home') }}" 
        class="px-3 md:px-4 py-2 text-sm font-medium text-ink hover:text-star transition-colors rounded focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2"
    >
        Home
    </a>
    <a 
        href="{{ route('games.index') }}" 
        class="px-3 md:px-4 py-2 text-sm font-medium text-ink hover:text-star transition-colors rounded focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2"
    >
        Games
    </a>
    <a 
        href="{{ route('about') }}" 
        class="px-3 md:px-4 py-2 text-sm font-medium text-ink hover:text-star transition-colors rounded focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2"
    >
        About
    </a>
</nav>

