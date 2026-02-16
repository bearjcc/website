<x-layouts.app title="Game not found - Ursa Minor">
    <div class="section py-12 md:py-16">
        <div class="max-w-[960px] mx-auto text-center space-y-6">
            <p class="text-4xl font-light tracking-wide text-ink/80" aria-hidden="true">404</p>
            <h1 class="h1 text-ink">Game not found</h1>
            <p class="text-ink/70 max-w-md mx-auto">
                That link may be wrong or the game is no longer here. Head back to the games list to find something to play.
            </p>
            <a href="{{ route('games.index') }}" class="btn-primary inline-flex">
                Browse games
            </a>
        </div>
    </div>
</x-layouts.app>
