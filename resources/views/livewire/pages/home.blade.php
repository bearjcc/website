<div>
    <x-slot:title>Home</x-slot:title>
    
    {{-- Hero Section - Minimal --}}
    <section class="section pt-24 md:pt-32 pb-20 md:pb-24" id="main-content">
        <div class="max-w-2xl mx-auto text-center space-y-8">
            <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" data-um-lockup="hero" />
            
            {{-- H1 for accessibility (visually hidden since logo serves as brand identity) --}}
            <h1 class="sr-only">Ursa Minor Games</h1>
            
            @php
                $tagline = __('ui.tagline');
                $lowercaseMode = config('ui.lowercase_mode', false);
            @endphp
            
            <p class="text-lg text-[color:var(--ink-muted)] {{ $lowercaseMode ? 'lowercase' : '' }}">
                {{ $tagline }}
            </p>

            <div class="pt-2">
                <div class="flex flex-wrap gap-3 justify-center">
                    <x-ui.flux-button 
                        variant="primary" 
                        :href="$firstPublishedGameSlug ? route('games.play', $firstPublishedGameSlug) : route('games.index')"
                        data-um-goal="hero_play_click"
                    >
                        {{ __('ui.cta_play') }}
                    </x-ui.flux-button>
                    
                    <x-ui.flux-button 
                        variant="secondary" 
                        :href="route('games.index')"
                    >
                        {{ __('ui.cta_browse') }}
                    </x-ui.flux-button>
                </div>
            </div>
        </div>
    </section>

    {{-- Games Carousel - Visual First with Embla --}}
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
            @endphp

            <x-ui.carousel>
                @foreach($games as $game)
                    <div class="flex-[0_0_100%] min-w-0 sm:flex-[0_0_50%] md:flex-[0_0_33.333%]">
                        <x-ui.game-card
                            :href="route('games.play', $game->slug)"
                            :title="$game->title"
                            :motif="$motifMap[$game->slug] ?? null"
                        />
                    </div>
                @endforeach
            </x-ui.carousel>
        </div>
    </section>
</div>
