<div class="section py-12 md:py-16 game-loading-placeholder" aria-busy="true" aria-label="{{ __('ui.loading') }}">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors" aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
        </div>
        <div class="game-loading-stars rounded-xl border border-[hsl(var(--border)/.1)] bg-[hsl(var(--space-700)/.6)] min-h-[320px] flex items-center justify-center">
            <div class="flex flex-col items-center gap-6">
                <div class="game-loading-dots flex gap-2" aria-hidden="true">
                    <span class="game-loading-star w-2 h-2 rounded-full bg-[hsl(var(--constellation))] opacity-60"></span>
                    <span class="game-loading-star game-loading-star-2 w-2 h-2 rounded-full bg-[hsl(var(--star)/.9)] opacity-70"></span>
                    <span class="game-loading-star game-loading-star-3 w-2 h-2 rounded-full bg-[hsl(var(--constellation))] opacity-50"></span>
                </div>
                <p class="text-ink/60 text-sm">{{ __('ui.loading') }}</p>
            </div>
        </div>
    </div>
</div>
