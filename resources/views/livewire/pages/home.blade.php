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
                currentRotation: 0,
                totalGames: {{ $totalGames }},
                isAnimating: false,
                get anglePerCard() { 
                    return 360 / this.totalGames; 
                },
                prev() { 
                    if (!this.isAnimating) {
                        this.isAnimating = true;
                        this.currentRotation += this.anglePerCard;
                        setTimeout(() => this.isAnimating = false, 600);
                    }
                },
                next() { 
                    if (!this.isAnimating) {
                        this.isAnimating = true;
                        this.currentRotation -= this.anglePerCard;
                        setTimeout(() => this.isAnimating = false, 600);
                    }
                },
                getCardRotation(index) {
                    return index * this.anglePerCard;
                },
                isCardVisible(index) {
                    const normalizedRotation = ((this.currentRotation % 360) + 360) % 360;
                    const cardAngle = ((this.getCardRotation(index) + normalizedRotation) % 360 + 360) % 360;
                    // Show cards that are roughly front-facing (within ~60 degrees of center)
                    return cardAngle < 60 || cardAngle > 300;
                }
            }" class="relative flex items-center gap-4 md:gap-6">
                
                {{-- Previous button - far left --}}
                @if($totalGames > 3)
                    <button 
                        @click="prev"
                        class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)]"
                        aria-label="Previous games">
                        <x-heroicon-o-chevron-left class="w-5 h-5 md:w-6 md:h-6 text-[color:var(--ink)]" />
                    </button>
                @endif

                {{-- 3D Carousel container --}}
                <div class="flex-1 carousel-viewport">
                    <div class="carousel-3d" 
                         :style="`transform: translateZ(-400px) rotateY(${currentRotation}deg)`">
                        @foreach($games as $index => $game)
                            <div class="carousel-item"
                                 :style="`transform: rotateY(${getCardRotation({{ $index }})}deg) translateZ(400px)`"
                                 :class="{ 'carousel-item-hidden': !isCardVisible({{ $index }}) }">
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
                        class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full glass grid place-items-center transition-all duration-150 hover:border-[color:var(--ink)]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)]"
                        aria-label="Next games">
                        <x-heroicon-o-chevron-right class="w-5 h-5 md:w-6 md:h-6 text-[color:var(--ink)]" />
                    </button>
                @endif
            </div>
        </div>
    </section>
</div>
