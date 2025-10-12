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
            <p class="winner-message">🎉 Player {{ $winner }} Wins! 🎉</p>
        @elseif($isDraw)
            <p class="draw-message">It's a Draw! 🤝</p>
        @else
            <p class="turn-message">
                Current Turn: <strong>{{ $currentPlayer }}</strong>
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

        .mode-selection {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .mode-selection h3 {
            color: var(--color-star-yellow, #fff89a);
            margin: 0 0 1rem 0;
            font-size: 1.3rem;
        }

        .mode-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ai-modes {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .mode-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .mode-button:hover {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .mode-button.active {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border-color: var(--color-star-yellow, #fff89a);
        }

        .game-status {
            text-align: center;
            margin-bottom: 2rem;
            min-height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .winner-message {
            color: var(--color-star-yellow, #fff89a);
            font-size: 1.5rem;
            font-weight: bold;
            animation: pulse 1s ease-in-out infinite;
        }

        .draw-message {
            color: #fff;
            font-size: 1.3rem;
        }

        .turn-message {
            color: #fff;
            font-size: 1.2rem;
        }

        .player-indicator {
            color: var(--color-star-yellow, #fff89a);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .board-container {
            margin-bottom: 2rem;
        }

        .game-board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            max-width: 400px;
            margin: 0 auto;
            aspect-ratio: 1;
        }

        .cell {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 248, 154, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 3rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100px;
        }

        .cell:not(.filled):not(:disabled):hover {
            background: rgba(255, 248, 154, 0.1);
            border-color: var(--color-star-yellow, #fff89a);
            transform: scale(1.05);
        }

        .cell:disabled {
            cursor: not-allowed;
        }

        .cell.filled {
            cursor: not-allowed;
        }

        .mark {
            animation: scaleIn 0.3s ease-out;
        }

        .mark.x {
            color: #ff6b6b;
        }

        .mark.o {
            color: #4ecdc4;
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
            margin-bottom: 1.5rem;
        }

        .new-game-button {
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

        .new-game-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 248, 154, 0.4);
        }

        .symbol-choice {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .symbol-choice p {
            margin: 0 0 0.5rem 0;
            color: #fff;
        }

        .symbol-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .symbol-button:hover {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .symbol-button.active {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border-color: var(--color-star-yellow, #fff89a);
        }

        .game-stats {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .game-stats p {
            margin: 0.5rem 0;
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
    </style>
</div>
