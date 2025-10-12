@php
    $title = 'Games - Ursa Minor Games';
    $description = 'Play free browser games including Tic-Tac-Toe, Sudoku, Chess, and more. Classic games reimagined for the web.';
@endphp

<x-layout :title="$title" :description="$description">
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
            
            <div class="games-grid">
                <!-- Tic Tac Toe -->
                <a href="{{ route('games.tic-tac-toe') }}" class="game-card">
                    <div class="game-icon">⭕❌</div>
                    <h3>Tic-Tac-Toe</h3>
                    <p>Classic 3x3 game. Play against a friend or challenge the AI at three difficulty levels.</p>
                    <span class="play-button">Play Now →</span>
                </a>

                <!-- Sudoku -->
                <a href="{{ route('games.sudoku') }}" class="game-card">
                    <div class="game-icon">🔢</div>
                    <h3>Sudoku</h3>
                    <p>Logic puzzle with numbers. Five difficulty levels from beginner to expert with hints and notes.</p>
                    <span class="play-button">Play Now →</span>
                </a>

                <!-- 2048 -->
                <a href="{{ route('games.2048') }}" class="game-card">
                    <div class="game-icon">2️⃣0️⃣4️⃣8️⃣</div>
                    <h3>2048</h3>
                    <p>Slide and combine tiles to reach 2048. Use arrow keys or WASD. Addictive puzzle action!</p>
                    <span class="play-button">Play Now →</span>
                </a>

                <!-- Coming Soon Games -->

                <div class="game-card coming-soon-card">
                    <div class="game-icon">♟️</div>
                    <h3>Chess</h3>
                    <p>The classic strategy game. Play against friends or challenge the AI.</p>
                    <span class="coming-soon-badge">Coming Soon</span>
                </div>

                <div class="game-card coming-soon-card">
                    <div class="game-icon">💣</div>
                    <h3>Minesweeper</h3>
                    <p>Clear the board without hitting mines. Test your logic and deduction skills.</p>
                    <span class="coming-soon-badge">Coming Soon</span>
                </div>

                <div class="game-card coming-soon-card">
                    <div class="game-icon">🃏</div>
                    <h3>Solitaire</h3>
                    <p>Classic Klondike Solitaire. Relax with this timeless card game.</p>
                    <span class="coming-soon-badge">Coming Soon</span>
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

    <style>
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .game-card {
            background: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--color-star-yellow, #fff89a);
            border-radius: 10px;
            padding: 2rem;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .game-card:hover {
            transform: translateX(10px);
            box-shadow: 0 8px 24px rgba(255, 248, 154, 0.2);
        }

        .game-card.coming-soon-card {
            opacity: 0.6;
            cursor: default;
        }

        .game-card.coming-soon-card:hover {
            transform: none;
            box-shadow: none;
        }

        .game-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .game-card h3 {
            color: var(--color-star-yellow, #fff89a);
            margin: 0 0 1rem 0;
            font-size: 1.5rem;
        }

        .game-card p {
            flex-grow: 1;
            margin: 0 0 1rem 0;
            line-height: 1.6;
        }

        .play-button {
            color: var(--color-star-yellow, #fff89a);
            font-weight: bold;
            font-size: 1.1rem;
        }

        .coming-soon-badge {
            display: inline-block;
            background: rgba(255, 248, 154, 0.2);
            color: var(--color-star-yellow, #fff89a);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .games-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-layout>

