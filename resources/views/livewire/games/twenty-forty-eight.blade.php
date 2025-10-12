<div class="game-2048" 
     x-data="{ showRules: false }"
     @keydown.window.arrow-up.prevent="$wire.move('up')"
     @keydown.window.arrow-down.prevent="$wire.move('down')"
     @keydown.window.arrow-left.prevent="$wire.move('left')"
     @keydown.window.arrow-right.prevent="$wire.move('right')"
     @keydown.window.w.prevent="$wire.move('up')"
     @keydown.window.s.prevent="$wire.move('down')"
     @keydown.window.a.prevent="$wire.move('left')"
     @keydown.window.d.prevent="$wire.move('right')">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>2048</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Use arrow keys (↑ ↓ ← →) or WASD to slide tiles</li>
            <li>When two tiles with the same number touch, they merge into one</li>
            <li>Reach the 2048 tile to win!</li>
            <li>Game is over when no moves are possible</li>
            <li>Try to get the highest score!</li>
        </ul>
        <p><strong>Controls:</strong></p>
        <ul>
            <li><strong>Arrow Keys</strong> or <strong>WASD</strong> - Move tiles</li>
            <li><strong>New Game button</strong> - Start fresh</li>
            <li><strong>Undo button</strong> - Take back last move (one only)</li>
        </ul>
    </div>

    <!-- Score Display -->
    <div class="score-display">
        <div class="score-box">
            <div class="score-label">Score</div>
            <div class="score-value">{{ number_format($score) }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Best</div>
            <div class="score-value">{{ number_format($bestScore) }}</div>
        </div>
    </div>

    <!-- Game Status -->
    @if($isWon && !$isOver)
        <div class="game-message win-message">
            <p>🎉 You reached 2048! 🎉</p>
            <p class="subtitle">Keep playing to increase your score!</p>
        </div>
    @elseif($isOver)
        <div class="game-message game-over-message">
            <p>Game Over!</p>
            <p class="subtitle">Final Score: {{ number_format($score) }}</p>
        </div>
    @endif

    <!-- Game Board -->
    <div class="board-container">
        <div class="game-board-2048">
            @foreach($board as $index => $value)
                @php
                    $row = intval($index / 4);
                    $col = $index % 4;
                @endphp
                <div class="tile-cell">
                    @if($value > 0)
                        <div class="tile tile-{{ $value }}" 
                             style="background-color: {{ $this->getTileColor($value) }}; 
                                    color: {{ $this->getTileTextColor($value) }};">
                            {{ $value }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Controls -->
    <div class="game-controls-2048">
        <div class="control-row">
            <button wire:click="newGame" class="control-btn-2048 new-game">
                🔄 New Game
            </button>
            
            <button wire:click="undo" 
                    class="control-btn-2048"
                    @disabled(empty($previousState))
                    title="Undo Last Move">
                ⏪ Undo
            </button>
        </div>
        
        <div class="mobile-controls">
            <div class="control-grid">
                <div></div>
                <button wire:click="move('up')" class="arrow-btn" title="Swipe or tap Up">↑</button>
                <div></div>
                <button wire:click="move('left')" class="arrow-btn" title="Swipe or tap Left">←</button>
                <div></div>
                <button wire:click="move('right')" class="arrow-btn" title="Swipe or tap Right">→</button>
                <div></div>
                <button wire:click="move('down')" class="arrow-btn" title="Swipe or tap Down">↓</button>
                <div></div>
            </div>
        </div>
    </div>

    <style>
        .game-2048 {
            max-width: 600px;
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
        }

        .score-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            padding: 1rem 2rem;
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
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--color-star-yellow, #fff89a);
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

        .win-message {
            animation: pulse 1s ease-in-out infinite;
        }

        .game-over-message {
            border-color: #ff6b6b;
        }

        .board-container {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }

        .game-board-2048 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background: rgba(0, 26, 51, 0.8);
            padding: 10px;
            border-radius: 12px;
            max-width: 450px;
            width: 100%;
            aspect-ratio: 1;
        }

        .tile-cell {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tile {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 2rem;
            animation: tile-appear 0.2s ease-out;
        }

        @keyframes tile-appear {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .game-controls-2048 {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .control-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .control-btn-2048 {
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

        .control-btn-2048:hover:not(:disabled) {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .control-btn-2048.new-game {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
        }

        .control-btn-2048:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .mobile-controls {
            display: none;
        }

        .control-grid {
            display: grid;
            grid-template-columns: repeat(3, 60px);
            grid-template-rows: repeat(3, 60px);
            gap: 8px;
            justify-content: center;
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
            .game-board-2048 {
                max-width: 350px;
            }

            .tile {
                font-size: 1.5rem;
            }

            .mobile-controls {
                display: block;
            }

            .score-display {
                flex-direction: column;
                align-items: center;
            }

            .score-box {
                width: 100%;
                max-width: 200px;
            }
        }

        @media (max-width: 400px) {
            .game-board-2048 {
                max-width: 280px;
            }

            .tile {
                font-size: 1.2rem;
            }
        }
    </style>
</div>
