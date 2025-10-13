<div class="minesweeper-game" x-data="{ showRules: false }">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>Minesweeper</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Left-click to reveal a square - numbers show adjacent mines</li>
            <li>Right-click to flag/unflag suspected mines</li>
            <li>Clear all safe squares without hitting a mine to win</li>
            <li>Use logic to deduce mine locations from the numbers</li>
        </ul>
        <p><strong>Difficulties:</strong></p>
        <ul>
            <li><strong>Beginner</strong>: 9×9 grid, 10 mines</li>
            <li><strong>Intermediate</strong>: 16×16 grid, 40 mines</li>
            <li><strong>Expert</strong>: 30×16 grid, 99 mines</li>
        </ul>
    </div>

    <!-- Difficulty Selection -->
    @if($showDifficultySelector && !$gameStarted)
        <div class="difficulty-selection">
            <h3>Select Difficulty</h3>
            <div class="difficulty-buttons">
                <button wire:click="selectDifficulty('beginner')" class="difficulty-btn">
                    Beginner<br><small>9×9 (10 mines)</small>
                </button>
                <button wire:click="selectDifficulty('intermediate')" class="difficulty-btn">
                    Intermediate<br><small>16×16 (40 mines)</small>
                </button>
                <button wire:click="selectDifficulty('expert')" class="difficulty-btn">
                    Expert<br><small>30×16 (99 mines)</small>
                </button>
            </div>
        </div>
    @endif

    <!-- Game Status -->
    <div class="game-status">
        <div class="status-grid">
            <div class="status-item">
                <span class="label">Mines:</span>
                <span class="value">{{ $mineCount - $flagsUsed }}</span>
            </div>
            <div class="status-item">
                <span class="label">Flags:</span>
                <span class="value">{{ $flagsUsed }}</span>
            </div>
            <div class="status-item">
                <span class="label">Revealed:</span>
                <span class="value">{{ $squaresRevealed }}</span>
            </div>
        </div>
        
        @if($gameWon)
            <p class="winner-message">You won. All mines found.</p>
        @elseif($gameOver && !$gameWon)
            <p class="game-over-message">Game over. Mine detonated.</p>
        @endif
    </div>

    <!-- Game Board -->
    <div class="board-container">
        <div class="minesweeper-board" style="grid-template-columns: repeat({{ $width }}, 1fr);">
            @foreach($board as $rowIndex => $row)
                @foreach($row as $cell)
                    @php
                        $cellClass = '';
                        if ($cell['revealed']) {
                            $cellClass = 'revealed';
                            if ($cell['type'] === 'mine') $cellClass .= ' exploded';
                        }
                        if ($cell['flagged']) $cellClass .= ' flagged';
                    @endphp
                    
                    <div class="mine-cell {{ $cellClass }}"
                         wire:click="revealCell({{ $cell['x'] }}, {{ $cell['y'] }})"
                         @contextmenu.prevent="$wire.flagCell({{ $cell['x'] }}, {{ $cell['y'] }})">
                        
                        @if($cell['flagged'])
                            <span class="flag">🚩</span>
                        @elseif($cell['revealed'])
                            @if($cell['type'] === 'mine')
                                <span class="mine">💣</span>
                            @elseif($cell['number'] > 0)
                                <span class="number number-{{ $cell['number'] }}">{{ $cell['number'] }}</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Game Controls -->
    <div class="game-controls">
        <button wire:click="newGame" class="control-btn new-game">
            🔄 New Game
        </button>
    </div>

    <style>
        .minesweeper-game {
            max-width: {{ $width > 16 ? '100%' : '700px' }};
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

        .difficulty-selection {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .difficulty-selection h3 {
            color: var(--color-star-yellow, #fff89a);
            margin: 0 0 1.5rem 0;
        }

        .difficulty-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .difficulty-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            min-width: 120px;
        }

        .difficulty-btn:hover {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border-color: var(--color-star-yellow, #fff89a);
            transform: translateY(-2px);
        }

        .difficulty-btn small {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .game-status {
            text-align: center;
            margin-bottom: 2rem;
        }

        .status-grid {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .status-item {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .status-item .label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .status-item .value {
            font-size: 1.3rem;
            color: var(--color-star-yellow, #fff89a);
            font-weight: bold;
        }

        .winner-message {
            color: var(--color-star-yellow, #fff89a);
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 1rem;
            animation: pulse 1s ease-in-out infinite;
        }

        .game-over-message {
            color: #ff6b6b;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 1rem;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .board-container {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            overflow-x: auto;
        }

        .minesweeper-board {
            display: grid;
            gap: 1px;
            background: rgba(255, 248, 154, 0.3);
            padding: 2px;
            border-radius: 4px;
            width: fit-content;
        }

        .mine-cell {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 248, 154, 0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.2s ease;
            width: 30px;
            height: 30px;
        }

        .mine-cell:hover:not(.revealed) {
            background: rgba(255, 248, 154, 0.2);
            transform: scale(1.05);
        }

        .mine-cell.revealed {
            background: rgba(0, 26, 51, 0.5);
            border-color: rgba(255, 255, 255, 0.2);
            cursor: default;
        }

        .mine-cell.flagged {
            background: rgba(255, 248, 154, 0.3);
        }

        .mine-cell.exploded {
            background: rgba(255, 107, 107, 0.8);
            animation: explode 0.5s ease-out;
        }

        @keyframes explode {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .flag {
            font-size: 1.2rem;
        }

        .mine {
            font-size: 1.2rem;
        }

        .number {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .number-1 { color: #0000ff; }
        .number-2 { color: #008000; }
        .number-3 { color: #ff0000; }
        .number-4 { color: #000080; }
        .number-5 { color: #800000; }
        .number-6 { color: #008080; }
        .number-7 { color: #000000; }
        .number-8 { color: #808080; }

        .game-controls {
            text-align: center;
        }

        .control-btn {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 248, 154, 0.4);
        }

        @media (max-width: 768px) {
            .mine-cell {
                width: 25px;
                height: 25px;
                font-size: 0.9rem;
            }

            .flag, .mine {
                font-size: 1rem;
            }

            .number {
                font-size: 0.9rem;
            }
        }
    </style>
</div>
