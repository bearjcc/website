<div>
    <x-slot:title>Games</x-slot:title>
    
    <div class="section py-12 md:py-16">
        @if($games->count() > 0)
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
        @else
            <div class="glass p-8 text-center">
                <p class="text-[color:var(--ink-muted)]">No games available yet. Check back soon.</p>
            </div>
        @endif
    </div>
</div>
