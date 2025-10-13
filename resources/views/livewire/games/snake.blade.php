<div class="snake-game" 
     x-data="{ 
         showRules: false,
         gameLoop: null,
         startLoop() {
             if (this.gameLoop) clearInterval(this.gameLoop);
             this.gameLoop = setInterval(() => {
                 if (@this.gameStarted && !@this.gameOver && !@this.paused) {
                     @this.tick();
                 }
             }, @this.speed);
         },
         stopLoop() {
             if (this.gameLoop) {
                 clearInterval(this.gameLoop);
                 this.gameLoop = null;
             }
         }
     }"
     x-init="startLoop(); $watch('$wire.speed', () => startLoop())"
     @keydown.window.arrow-up.prevent="@this.changeDirection('up')"
     @keydown.window.arrow-down.prevent="@this.changeDirection('down')"
     @keydown.window.arrow-left.prevent="@this.changeDirection('left')"
     @keydown.window.arrow-right.prevent="@this.changeDirection('right')"
     @keydown.window.w.prevent="@this.changeDirection('up')"
     @keydown.window.s.prevent="@this.changeDirection('down')"
     @keydown.window.a.prevent="@this.changeDirection('left')"
     @keydown.window.d.prevent="@this.changeDirection('right')"
     @keydown.window.space.prevent="@this.togglePause()">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>Snake</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Use arrow keys or WASD to control the snake's direction</li>
            <li>Guide the snake to eat food (green apple) to grow longer</li>
            <li>Avoid hitting walls or the snake's own body</li>
            <li>Game speeds up every 5 food items eaten</li>
            <li>Press SPACE to pause/unpause the game</li>
        </ul>
    </div>

    <!-- Score Display -->
    <div class="score-display">
        <div class="score-box">
            <div class="score-label">Score</div>
            <div class="score-value">{{ $score }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Level</div>
            <div class="score-value">{{ $level }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Best</div>
            <div class="score-value">{{ $highScore }}</div>
        </div>
    </div>

    <!-- Game Status -->
    @if(!$gameStarted)
        <div class="start-message">
            <button wire:click="startGame" class="start-button">
                Start
            </button>
            <p>Use arrow keys or WASD to move!</p>
        </div>
    @elseif($gameOver)
        <div class="game-message game-over-message">
            <p>Game Over!</p>
            <p class="subtitle">Final Score: {{ $score }}</p>
            <p class="subtitle">Length: {{ count($snake) }}</p>
        </div>
    @elseif($paused)
        <div class="game-message pause-message">
            <p>⏸️ Paused</p>
            <p class="subtitle">Press SPACE to resume</p>
        </div>
    @endif

    <!-- Game Board -->
    <div class="board-container">
        <div class="snake-board">
            @for($y = 0; $y < 15; $y++)
                @for($x = 0; $x < 20; $x++)
                    @php
                        $isSnakeHead = !empty($snake) && $snake[0]['x'] === $x && $snake[0]['y'] === $y;
                        $isSnakeBody = false;
                        foreach(array_slice($snake, 1) as $segment) {
                            if ($segment['x'] === $x && $segment['y'] === $y) {
                                $isSnakeBody = true;
                                break;
                            }
                        }
                        $isFood = $food['x'] === $x && $food['y'] === $y;
                    @endphp
                    
                    <div class="snake-cell 
                                {{ $isSnakeHead ? 'snake-head' : '' }}
                                {{ $isSnakeBody ? 'snake-body' : '' }}
                                {{ $isFood ? 'food' : '' }}">
                        @if($isSnakeHead)
                            🐍
                        @elseif($isSnakeBody)
                            ●
                        @elseif($isFood)
                            🍎
                        @endif
                    </div>
                @endfor
            @endfor
        </div>
    </div>

    <!-- Game Controls -->
    <div class="game-controls-snake">
        <button wire:click="newGame" class="control-btn new-game">
            🔄 New Game
        </button>
        
        @if($gameStarted && !$gameOver)
            <button wire:click="togglePause" class="control-btn">
                {{ $paused ? '▶️ Resume' : '⏸️ Pause' }}
            </button>
        @endif
        
        <div class="mobile-controls">
            <div class="control-grid">
                <div></div>
                <button @click="@this.changeDirection('up')" class="arrow-btn">↑</button>
                <div></div>
                <button @click="@this.changeDirection('left')" class="arrow-btn">←</button>
                <div></div>
                <button @click="@this.changeDirection('right')" class="arrow-btn">→</button>
                <div></div>
                <button @click="@this.changeDirection('down')" class="arrow-btn">↓</button>
                <div></div>
            </div>
        </div>
    </div>

    <style>
        .snake-game {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .game-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .game-header h2 {
            color: var(--color-star-yellow, #fff89a);
            margin: 0;
            font-size: 2rem;
        }

        .rules-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 248, 154, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .rules-button:hover {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .game-rules {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid var(--color-star-yellow, #fff89a);
            margin-bottom: 2rem;
        }

        .game-rules ul {
            margin: 0.5rem 0 0 1.5rem;
        }

        .game-rules li {
            margin: 0.5rem 0;
        }

        .score-display {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .score-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            border: 2px solid rgba(255, 248, 154, 0.3);
            text-align: center;
        }

        .score-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.25rem;
        }

        .score-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-star-yellow, #fff89a);
        }

        .start-message {
            text-align: center;
            margin-bottom: 2rem;
        }

        .start-button {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border: none;
            padding: 1.5rem 3rem;
            border-radius: 8px;
            font-size: 1.3rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .start-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 248, 154, 0.4);
        }

        .game-message {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 10px;
            border: 3px solid var(--color-star-yellow, #fff89a);
            margin-bottom: 2rem;
            text-align: center;
        }

        .game-message p {
            margin: 0.5rem 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-star-yellow, #fff89a);
        }

        .game-message .subtitle {
            font-size: 1rem;
            color: white;
            font-weight: normal;
        }

        .game-over-message {
            border-color: #ff6b6b;
        }

        .pause-message {
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .board-container {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }

        .snake-board {
            display: grid;
            grid-template-columns: repeat(20, 1fr);
            gap: 1px;
            background: rgba(0, 26, 51, 0.8);
            padding: 4px;
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
        }

        .snake-cell {
            background: rgba(0, 0, 0, 0.5);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            border-radius: 2px;
        }

        .snake-cell.snake-head {
            background: var(--color-star-yellow, #fff89a);
            font-size: 1rem;
        }

        .snake-cell.snake-body {
            background: #4ecdc4;
            color: white;
        }

        .snake-cell.food {
            background: rgba(255, 107, 107, 0.3);
            font-size: 1rem;
        }

        .game-controls-snake {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items: center;
        }

        .control-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 0.75rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: bold;
        }

        .control-btn:hover {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .control-btn.new-game {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
        }

        .mobile-controls {
            display: none;
        }

        .control-grid {
            display: grid;
            grid-template-columns: repeat(3, 60px);
            grid-template-rows: repeat(3, 60px);
            gap: 8px;
        }

        .arrow-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .arrow-btn:hover, .arrow-btn:active {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .snake-board {
                max-width: 400px;
            }

            .snake-cell {
                font-size: 0.6rem;
            }

            .snake-cell.snake-head,
            .snake-cell.food {
                font-size: 0.8rem;
            }

            .mobile-controls {
                display: block;
            }

            .score-display {
                gap: 0.5rem;
            }

            .score-box {
                padding: 0.75rem 1rem;
            }

            .score-label {
                font-size: 0.8rem;
            }

            .score-value {
                font-size: 1.2rem;
            }
        }
    </style>
</div>
