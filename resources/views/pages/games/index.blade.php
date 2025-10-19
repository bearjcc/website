@php
    $title = 'Games - Ursa Minor Games';
    $description = 'Play free browser games including Tic-Tac-Toe, Sudoku, Chess, and more. Classic games reimagined for the web.';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="hero-section">
        <article>
            <h1>Browser Games</h1>
            <p class="tagline">Classic games, reimagined for the web</p>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Play Free Games</h2>
            <p>
                Challenge yourself, compete with friends, or test your skills against AI opponents. 
                All games are free to play, no registration required.
            </p>
        </article>
    </section>

    <section class="coming-soon">
        <article>
            <h2>Available Games</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mt-8">
                @php
                    $publishedGames = \App\Models\Game::where('status', 'published')->get();
                    
                    // Map game slugs to motifs
                    $motifMap = [
                        'tic-tac-toe' => 'tictactoe',
                        'connect-4' => 'connect4',
                        'sudoku' => 'sudoku',
                        'minesweeper' => 'minesweeper',
                        'snake' => 'snake',
                        'twenty-forty-eight' => '2048',
                    ];
                @endphp

                @foreach($publishedGames as $game)
                    <x-ui.game-card
                        :href="route('games.play', $game->slug)"
                        :title="$game->title"
                        :motif="$motifMap[$game->slug] ?? 'sparkles'"
                    />
                @endforeach

                <!-- Coming Soon Games -->
                <div class="um-game-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden opacity-60">
                    <div class="um-motif absolute inset-0 grid place-items-center">
                        <x-heroicon-o-sparkles class="w-14 h-14 text-ink/70" />
                    </div>
                    <div class="pt-[70%] md:pt-[66%]"></div>
                    <div class="absolute left-4 right-4 bottom-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[hsl(var(--surface)/.24)] border border-[hsl(var(--border)/.12)] backdrop-blur">
                            <span class="text-ink text-sm font-medium">Chess</span>
                            <span class="inline-block px-2 py-0.5 bg-star/20 text-star rounded text-xs font-semibold">Soon</span>
                        </div>
                    </div>
                </div>

                <div class="um-game-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden opacity-60">
                    <div class="um-motif absolute inset-0 grid place-items-center">
                        <x-heroicon-o-sparkles class="w-14 h-14 text-ink/70" />
                    </div>
                    <div class="pt-[70%] md:pt-[66%]"></div>
                    <div class="absolute left-4 right-4 bottom-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[hsl(var(--surface)/.24)] border border-[hsl(var(--border)/.12)] backdrop-blur">
                            <span class="text-ink text-sm font-medium">Solitaire</span>
                            <span class="inline-block px-2 py-0.5 bg-star/20 text-star rounded text-xs font-semibold">Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="cta-section">
        <article>
            <h2>More Games Coming Soon</h2>
            <p>
                We're actively adding more games to the platform. Check back regularly for new additions!
            </p>
            <p class="disclaimer">
                <em>Have a game request? Let us know on <a href="https://github.com/bearjcc/website" target="_blank" rel="noopener">GitHub</a>!</em>
            </p>
        </article>
    </section>

</x-layouts.app>

