<div class="section py-12 md:py-16">
    <div class="max-w-2xl mx-auto text-center space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('games.index') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">Game Not Found</h1>
        </div>

        <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-8">
            <x-heroicon-o-exclamation-triangle class="w-16 h-16 text-ink/50 mx-auto mb-4" />
            <h2 class="h4 text-ink mb-4">Game Not Available</h2>
            <p class="text-ink/70 mb-6">
                This game is not available yet or may have been moved.
            </p>
            <a href="{{ route('games.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-star text-space-900 font-semibold hover:bg-star/92 transition-all">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Back to Games
            </a>
        </div>
    </div>
</div>
