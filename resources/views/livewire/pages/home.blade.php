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

    {{-- Games Grid - Visual First --}}
    <section class="py-12 md:py-16">
        <div class="section">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
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

                @foreach($games as $game)
                    <x-ui.game-card
                        :href="route('games.play', $game->slug)"
                        :title="$game->title"
                        :motif="$motifMap[$game->slug] ?? null"
                    />
                @endforeach
            </div>
        </div>
    </section>
</div>
