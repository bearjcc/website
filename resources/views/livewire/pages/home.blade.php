<div>
    <x-slot:title>Home</x-slot:title>
    
    {{-- Hero Section - Minimal --}}
    <section class="pt-8 pl-8 pb-20 md:pb-24" id="main-content">
        <div class="max-w-2xl mx-auto text-center space-y-8">
            <div class="flex justify-start mb-8">
                <x-ui.logo-lockup data-um-lockup="hero" />
            </div>
            
            {{-- H1 for accessibility (visually hidden since logo serves as brand identity) --}}
            <h1 class="sr-only">Ursa Minor</h1>
            
            @php
                $tagline = __('ui.tagline');
                $lowercaseMode = config('ui.lowercase_mode', false);
            @endphp
            
            <p class="text-lg text-[color:var(--ink-muted)] {{ $lowercaseMode ? 'lowercase' : '' }}">
                {{ $tagline }}
            </p>

        </div>
    </section>

    {{-- Games Section - Clear and obvious --}}
    <section class="py-12 md:py-16 pb-20">
        <div class="section">
            <div class="max-w-4xl mx-auto">
                <h2 class="h3 text-center text-ink mb-8">Free Games to Play</h2>
                
                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
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
                            'twenty-forty-eight' => '2048',
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
        </div>
    </section>
</div>
