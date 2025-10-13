<div class="tic-tac-toe-game" x-data="{ showRules: false }">
    <!-- Game Header -->
    <div class="game-header">
        <h2>Tic-Tac-Toe</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Get three of your symbols in a row (horizontal, vertical, or diagonal) to win</li>
            <li>Take turns placing your symbol (X or O) in an empty square</li>
            <li>If all squares are filled and no one has won, it's a draw</li>
            <li>Choose to play against a friend or challenge the AI at three difficulty levels</li>
        </ul>
    </div>

    <!-- Game Mode Selection -->
    @if($movesCount === 0)
        <div class="mode-selection">
            <h3>Select Game Mode</h3>
            
            <div class="mode-options">
                <button wire:click="setGameMode('pvp')" 
                        class="mode-button {{ $gameMode === 'pvp' ? 'active' : '' }}">
                    Player vs Player
                </button>
                
                <div class="ai-modes">
                    <button wire:click="setGameMode('ai-easy', 'X')" 
                            class="mode-button {{ $gameMode === 'ai-easy' ? 'active' : '' }}">
                        AI - Easy
                    </button>
                    <button wire:click="setGameMode('ai-medium', 'X')" 
                            class="mode-button {{ $gameMode === 'ai-medium' ? 'active' : '' }}">
                        AI - Medium
                    </button>
                    <button wire:click="setGameMode('ai-hard', 'X')" 
                            class="mode-button {{ $gameMode === 'ai-hard' ? 'active' : '' }}">
                        AI - Hard
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Game Status -->
    <div class="game-status">
        @if($winner)
            <p class="winner-message">Player {{ $winner }} wins.</p>
        @elseif($isDraw)
            <p class="draw-message">Draw.</p>
        @else
            <p class="turn-message">
                Current turn: <strong>{{ $currentPlayer }}</strong>
                @if($gameMode !== 'pvp')
                    <span class="player-indicator">
                        (You are {{ $playerSymbol }})
                    </span>
                @endif
            </p>
        @endif
    </div>

    <!-- Game Board -->
    <div class="board-container">
        <div class="game-board">
            @foreach($board as $index => $cell)
                <button 
                    wire:click="makeMove({{ $index }})"
                    class="cell {{ $cell !== null ? 'filled' : '' }} {{ $cell === 'X' ? 'x-mark' : ($cell === 'O' ? 'o-mark' : '') }}"
                    @disabled($cell !== null || $winner !== null || $isDraw)
                >
                    @if($cell === 'X')
                        <span class="mark x">X</span>
                    @elseif($cell === 'O')
                        <span class="mark o">O</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- Game Controls -->
    <div class="game-controls">
        <button wire:click="newGame" class="new-game-button">
            New Game
        </button>
        
        @if($movesCount === 0 && $gameMode !== 'pvp')
            <div class="symbol-choice">
                <p>Choose your symbol:</p>
                <button wire:click="setGameMode('{{ $gameMode }}', 'X')" 
                        class="symbol-button {{ $playerSymbol === 'X' ? 'active' : '' }}">
                    X (goes first)
                </button>
                <button wire:click="setGameMode('{{ $gameMode }}', 'O')" 
                        class="symbol-button {{ $playerSymbol === 'O' ? 'active' : '' }}">
                    O (goes second)
                </button>
            </div>
        @endif
    </div>

    <!-- Game Stats -->
    <div class="game-stats">
        <p>Moves: {{ $movesCount }}</p>
        <p>Mode: 
            @if($gameMode === 'pvp')
                Player vs Player
            @else
                vs AI ({{ ucfirst(str_replace('ai-', '', $gameMode)) }})
            @endif
        </p>
    </div>

    <style>
        .tic-tac-toe-game {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .game-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .game-header h2 {
            color: hsl(var(--star));
            margin: 0;
            font-size: 2rem;
        }

        .rules-button {
            background: hsl(var(--surface) / .1);
            color: hsl(var(--ink));
            border: 1px solid hsl(var(--border) / .3);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .rules-button:hover {
            background: hsl(var(--surface) / .2);
            border-color: hsl(var(--star));
        }

        .game-rules {
            background: hsl(var(--surface) / .3);
            backdrop-filter: blur(10px);
            padding: 24px;
            border-radius: 12px;
            border-left: 4px solid hsl(var(--star));
            margin-bottom: 32px;
        }

        .game-rules ul {
            margin: 8px 0 0 24px;
        }

        .game-rules li {
            margin: 8px 0;
        }

        .mode-selection {
            background: hsl(var(--surface) / .05);
            backdrop-filter: blur(5px);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 32px;
        }

        .mode-selection h3 {
            color: hsl(var(--star));
            margin: 0 0 16px 0;
            font-size: 1.3rem;
        }

        .mode-options {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .ai-modes {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .mode-button {
            background: hsl(var(--surface) / .1);
            color: hsl(var(--ink));
            border: 2px solid hsl(var(--border) / .3);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 1rem;
        }

        .mode-button:hover {
            background: hsl(var(--surface) / .2);
            border-color: hsl(var(--star));
        }

        .mode-button.active {
            background: hsl(var(--star));
            color: hsl(220 20% 10%);
            border-color: hsl(var(--star));
        }

        .game-status {
            text-align: center;
            margin-bottom: 32px;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .winner-message {
            color: hsl(var(--star));
            font-size: 1.5rem;
            font-weight: 600;
        }

        .draw-message {
            color: hsl(var(--constellation));
            font-size: 1.3rem;
        }

        .turn-message {
            color: hsl(var(--ink));
            font-size: 1.2rem;
        }

        .player-indicator {
            color: hsl(var(--star));
        }

        .board-container {
            margin-bottom: 32px;
        }

        .game-board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-width: 400px;
            margin: 0 auto;
            aspect-ratio: 1;
        }

        .cell {
            background: hsl(var(--surface) / .05);
            border: 2px solid hsl(var(--border) / .3);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 3rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100px;
        }

        .cell:not(.filled):not(:disabled):hover {
            background: hsl(var(--surface) / .1);
            border-color: hsl(var(--star));
            transform: translateY(-2px);
        }

        .cell:disabled {
            cursor: not-allowed;
        }

        .cell.filled {
            cursor: not-allowed;
        }

        .mark {
            animation: scaleIn 0.15s ease-out;
        }

        .mark.x {
            color: hsl(0 70% 70%);
        }

        .mark.o {
            color: hsl(var(--constellation));
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .game-controls {
            text-align: center;
            margin-bottom: 24px;
        }

        .new-game-button {
            background: hsl(var(--star));
            color: hsl(220 20% 10%);
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .new-game-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px hsl(var(--star) / .4);
        }

        .symbol-choice {
            margin-top: 16px;
            padding: 16px;
            background: hsl(var(--surface) / .05);
            border-radius: 8px;
        }

        .symbol-choice p {
            margin: 0 0 8px 0;
            color: hsl(var(--ink));
        }

        .symbol-button {
            background: hsl(var(--surface) / .1);
            color: hsl(var(--ink));
            border: 2px solid hsl(var(--border) / .3);
            padding: 8px 24px;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 8px;
            transition: all 0.15s ease;
        }

        .symbol-button:hover {
            background: hsl(var(--surface) / .2);
            border-color: hsl(var(--star));
        }

        .symbol-button.active {
            background: hsl(var(--star));
            color: hsl(220 20% 10%);
            border-color: hsl(var(--star));
        }

        .game-stats {
            text-align: center;
            color: hsl(var(--ink-muted));
            font-size: 0.9rem;
        }

        .game-stats p {
            margin: 8px 0;
        }

        @media (max-width: 768px) {
            .game-board {
                max-width: 300px;
            }

            .cell {
                font-size: 2rem;
                min-height: 80px;
            }

            .game-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .cell:hover,
            .new-game-button:hover {
                transform: none;
            }
            .mark {
                animation: none;
            }
        }
    </style>
</div>
