{{-- Simple navigation links for app layout --}}
<nav aria-label="Main navigation" class="flex items-center gap-6">
    <a 
        href="{{ route('home') }}" 
        class="text-sm font-medium text-[color:var(--ink)] hover:text-[color:var(--star)] transition-colors focus-visible:outline-2 focus-visible:outline-[color:var(--star)]"
    >
        Home
    </a>
    <a 
        href="{{ route('games.index') }}" 
        class="text-sm font-medium text-[color:var(--ink)] hover:text-[color:var(--star)] transition-colors focus-visible:outline-2 focus-visible:outline-[color:var(--star)]"
    >
        Games
    </a>
    <a 
        href="{{ route('blog.index') }}" 
        class="text-sm font-medium text-[color:var(--ink)] hover:text-[color:var(--star)] transition-colors focus-visible:outline-2 focus-visible:outline-[color:var(--star)]"
    >
        Blog
    </a>
    <a 
        href="{{ route('about') }}" 
        class="text-sm font-medium text-[color:var(--ink)] hover:text-[color:var(--star)] transition-colors focus-visible:outline-2 focus-visible:outline-[color:var(--star)]"
    >
        About
    </a>
    <a 
        href="#contact" 
        class="text-sm font-medium text-[color:var(--ink)] hover:text-[color:var(--star)] transition-colors focus-visible:outline-2 focus-visible:outline-[color:var(--star)]"
    >
        Contact
    </a>
</nav>

