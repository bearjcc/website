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
    <section class="py-12 md:py-16">
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
                get totalPages() { return Math.ceil(this.totalGames / this.gamesPerPage); },
                get canGoPrev() { return this.currentIndex > 0; },
                get canGoNext() { return this.currentIndex < this.totalPages - 1; },
                prev() { if (this.canGoPrev) this.currentIndex--; },
                next() { if (this.canGoNext) this.currentIndex++; },
                isVisible(gameIndex) {
                    const startIndex = this.currentIndex * this.gamesPerPage;
                    const endIndex = startIndex + this.gamesPerPage;
                    return gameIndex >= startIndex && gameIndex < endIndex;
                }
            }" class="relative">
                
                {{-- Carousel container --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($games as $index => $game)
                        <div x-show="isVisible({{ $index }})" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="w-full">
                            <x-ui.game-card
                                :href="route('games.play', $game->slug)"
                                :title="$game->title"
                                :motif="$motifMap[$game->slug] ?? null"
                            />
                        </div>
                    @endforeach
                </div>

                {{-- Navigation buttons - minimal, elegant --}}
                @if($totalGames > 3)
                    <div class="flex items-center justify-center gap-3 mt-6">
                        {{-- Previous button --}}
                        <button 
                            @click="prev"
                            :disabled="!canGoPrev"
                            :class="{ 'opacity-30 cursor-not-allowed': !canGoPrev }"
                            class="w-10 h-10 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] disabled:hover:border-[color:var(--ink)]/10"
                            aria-label="Previous games">
                            <x-heroicon-o-chevron-left class="w-5 h-5 text-[color:var(--ink)]" />
                        </button>

                        {{-- Page indicators --}}
                        <div class="flex gap-2" role="tablist" aria-label="Game carousel pages">
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

                        {{-- Next button --}}
                        <button 
                            @click="next"
                            :disabled="!canGoNext"
                            :class="{ 'opacity-30 cursor-not-allowed': !canGoNext }"
                            class="w-10 h-10 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] disabled:hover:border-[color:var(--ink)]/10"
                            aria-label="Next games">
                            <x-heroicon-o-chevron-right class="w-5 h-5 text-[color:var(--ink)]" />
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
