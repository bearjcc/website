<x-layout>
    <section class="hero-section">
        <article>
            <h1>Welcome to Ursa Minor</h1>
            <p class="tagline">Where games are born under the stars</p>
            
            <p class="intro">
                Ursa Minor is a game development brand focused on creating memorable gaming experiences—from classic 
                browser games to innovative board games and beyond. We believe great games start with great ideas, 
                careful planning, and a community of passionate players.
            </p>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Our Vision</h2>
            <p>
                One day, we dream of opening a board game café in New Zealand—a place where friends gather, 
                strategies unfold, and new adventures begin. But every great journey starts with a single step.
            </p>
            <p>
                Before that dream becomes reality, we're building something special: a platform where we can 
                test game mechanics, explore world-building, share original board game designs, and create a 
                community around the love of gaming.
            </p>
        </article>
    </section>

    <section class="coming-soon">
        <article>
            <h2>Available Now</h2>
            
            <div class="feature">
                <h3>Browser Games</h3>
                <p>
                    Play 5 classic games now: Tic-Tac-Toe, Sudoku, 2048, Minesweeper, and Snake! 
                    All free to play with AI opponents, difficulty levels, and mobile support.
                </p>
                <p>
                    <a href="{{ route('games.index') }}" style="color: var(--color-star-yellow, #fff89a); font-weight: bold; text-decoration: none;">
                        Play Games Now →
                    </a>
                </p>
            </div>

            <div class="feature">
                <h3>F1 Predictions</h3>
                <p>
                    Join our community to predict race outcomes, earn points, and climb the seasonal leaderboard. 
                    Perfect for Formula 1 fans who want to add an extra layer of excitement to race weekends.
                </p>
            </div>

            <div class="feature">
                <h3>Board Games</h3>
                <p>
                    Original board game designs available digitally and as print-and-play downloads. 
                    Playtest new mechanics, provide feedback, and be part of the creative process.
                </p>
            </div>

            <div class="feature">
                <h3>World Building</h3>
                <p>
                    Explore the lore, maps, and stories behind our ambitious video game project. 
                    A collaborative space for writers, artists, and world-builders to contribute to something epic.
                </p>
            </div>
        </article>
    </section>

    <section class="cta-section">
        <article>
            <h2>Start Playing Now!</h2>
            <p>
                We've just launched 5 browser games and we're adding more every week. 
                No registration required, completely free to play, works on mobile and desktop.
            </p>
            <p>
                <a href="{{ route('games.index') }}" class="cta-button" 
                   style="display: inline-block; background: var(--color-star-yellow, #fff89a); color: #000; 
                          padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: bold; 
                          font-size: 1.2rem; margin: 1rem 0; transition: all 0.3s ease;">
                    🎮 Play Games
                </a>
            </p>
            <p class="disclaimer">
                <em>5 games live now, more coming soon!</em>
            </p>
        </article>
    </section>
</x-layout>

