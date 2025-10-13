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
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                <!-- Tic Tac Toe -->
                <x-ui.flux-card 
                    heading="Tic-Tac-Toe"
                    :href="route('games.tic-tac-toe')"
                >
                    <p>Classic 3x3 game. Play against a friend or challenge the AI at three difficulty levels.</p>
                </x-ui.flux-card>

                <!-- Sudoku -->
                <x-ui.flux-card 
                    heading="Sudoku"
                    :href="route('games.sudoku')"
                >
                    <p>Logic puzzle with numbers. Five difficulty levels from beginner to expert with hints and notes.</p>
                </x-ui.flux-card>

                <!-- 2048 -->
                <x-ui.flux-card 
                    heading="2048"
                    :href="route('games.2048')"
                >
                    <p>Slide and combine tiles to reach 2048. Use arrow keys or WASD. Addictive puzzle action!</p>
                </x-ui.flux-card>

                <!-- Minesweeper -->
                <x-ui.flux-card 
                    heading="Minesweeper"
                    :href="route('games.minesweeper')"
                >
                    <p>Classic mine-finding puzzle. Left-click to reveal, right-click to flag. Three difficulty levels!</p>
                </x-ui.flux-card>

                <!-- Snake -->
                <x-ui.flux-card 
                    heading="Snake"
                    :href="route('games.snake')"
                >
                    <p>Classic arcade game. Eat food to grow, avoid walls and yourself. Speeds up as you grow!</p>
                </x-ui.flux-card>

                <!-- Connect 4 -->
                <x-ui.flux-card 
                    heading="Connect 4"
                    :href="route('games.connect4')"
                >
                    <p>Drop pieces to get 4 in a row. Classic strategy game for two players. Vertical, horizontal, or diagonal!</p>
                </x-ui.flux-card>

                <!-- Coming Soon Games -->
                <x-ui.flux-card heading="Chess" class="opacity-60">
                    <p>The classic strategy game. Play against friends or challenge the AI.</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-star/20 text-star rounded text-sm font-semibold">Coming Soon</span>
                </x-ui.flux-card>

                <x-ui.flux-card heading="Solitaire" class="opacity-60">
                    <p>Classic Klondike Solitaire. Relax with this timeless card game.</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-star/20 text-star rounded text-sm font-semibold">Coming Soon</span>
                </x-ui.flux-card>
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

