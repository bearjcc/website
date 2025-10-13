<div>
    <x-slot:title>Home</x-slot:title>
    
    {{-- Hero Section - Minimal --}}
    <section class="section pt-24 md:pt-32 pb-20 md:pb-24" id="main-content">
        <div class="max-w-2xl mx-auto text-center space-y-8">
            <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" />
            
            @php
                $tagline = __('ui.tagline');
                $lowercaseMode = config('ui.lowercase_mode', false);
            @endphp
            
            <p class="text-lg text-[color:var(--ink-muted)] {{ $lowercaseMode ? 'lowercase' : '' }}">
                {{ $tagline }}
            </p>

            <div class="pt-2">
                <x-ui.cta-row
                    :primaryHref="$firstPublishedGameSlug ? route('games.play', $firstPublishedGameSlug) : route('games.index')"
                    :primaryLabel="__('ui.cta_play')"
                    :secondaryHref="route('games.index')"
                    :secondaryLabel="__('ui.cta_browse')"
                    data-um-goal="hero_play_click"
                />
            </div>
        </div>
    </section>

    {{-- Games Carousel - Visual First --}}
    <section class="py-12 md:py-16 pb-20">
        <div class="section">
            @php
                // Map game slugs to motifs
                $motifMap = [
                    'tic-tac-toe' => 'tictactoe',
                    'connect-4' => 'connect4',
                    'sudoku' => 'sudoku',
                    'chess' => 'chess',
                    'checkers' => 'checkers',
                    'minesweeper' => 'minesweeper',
                    'snake' => 'snake',
                    '2048' => '2048',
                ];
                $gamesArray = $games->toArray();
                $totalGames = count($gamesArray);
            @endphp

            <div x-data="{ 
                currentIndex: 0,
                gamesPerPage: 3,
                totalGames: {{ $totalGames }},
                isAnimating: false,
                get totalPages() { return Math.ceil(this.totalGames / this.gamesPerPage); },
                get canGoPrev() { return this.currentIndex > 0; },
                get canGoNext() { return this.currentIndex < this.totalPages - 1; },
                prev() { 
                    if (this.canGoPrev && !this.isAnimating) {
                        this.isAnimating = true;
                        this.currentIndex--;
                        setTimeout(() => this.isAnimating = false, 500);
                    }
                },
                next() { 
                    if (this.canGoNext && !this.isAnimating) {
                        this.isAnimating = true;
                        this.currentIndex++;
                        setTimeout(() => this.isAnimating = false, 500);
                    }
                },
                isVisible(gameIndex) {
                    const startIndex = this.currentIndex * this.gamesPerPage;
                    const endIndex = startIndex + this.gamesPerPage;
                    return gameIndex >= startIndex && gameIndex < endIndex;
                },
                getCardClass(gameIndex) {
                    const startIndex = this.currentIndex * this.gamesPerPage;
                    const endIndex = startIndex + this.gamesPerPage;
                    const isVisible = gameIndex >= startIndex && gameIndex < endIndex;
                    return isVisible ? 'carousel-card-active' : 'carousel-card-hidden';
                }
            }" class="relative flex items-center gap-4">
                
                {{-- Previous button - far left --}}
                @if($totalGames > 3)
                    <button 
                        @click="prev"
                        :disabled="!canGoPrev"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoPrev }"
                        class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] disabled:hover:border-[color:var(--ink)]/10"
                        aria-label="Previous games">
                        <x-heroicon-o-chevron-left class="w-5 h-5 md:w-6 md:h-6 text-[color:var(--ink)]" />
                    </button>
                @endif

                {{-- Carousel container with overflow hidden --}}
                <div class="flex-1 overflow-hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        @foreach($games as $index => $game)
                            <div :class="getCardClass({{ $index }})" 
                                 class="w-full transition-all duration-500 ease-in-out">
                                <x-ui.game-card
                                    :href="route('games.play', $game->slug)"
                                    :title="$game->title"
                                    :motif="$motifMap[$game->slug] ?? null"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Next button - far right --}}
                @if($totalGames > 3)
                    <button 
                        @click="next"
                        :disabled="!canGoNext"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoNext }"
                        class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] disabled:hover:border-[color:var(--ink)]/10"
                        aria-label="Next games">
                        <x-heroicon-o-chevron-right class="w-5 h-5 md:w-6 md:h-6 text-[color:var(--ink)]" />
                    </button>
                @endif

                {{-- Page indicators at bottom --}}
                @if($totalGames > 3)
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex gap-2" role="tablist" aria-label="Game carousel pages">
                        <template x-for="page in totalPages" :key="page">
                            <button 
                                @click="currentIndex = page - 1"
                                :class="currentIndex === page - 1 ? 'bg-[color:var(--star)]' : 'bg-[color:var(--ink)]/20'"
                                class="w-2 h-2 rounded-full transition-all duration-150 hover:bg-[color:var(--ink)]/40"
                                :aria-label="`Go to page ${page}`"
                                :aria-selected="currentIndex === page - 1">
                            </button>
                        </template>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
