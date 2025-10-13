<div class="connect4-game" x-data="{ showRules: @entangle('showRules') }">
    <!-- Game Header -->
    <div class="game-header">
        <h2>Connect 4</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            @foreach((new \App\Games\Connect4\Connect4Game())->rules() as $rule)
                <li>{{ $rule }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Game Status -->
    <div class="game-status">
        @if($state['gameOver'])
            @if($state['winner'] === 'draw')
                <p class="draw-message">Draw.</p>
            @else
                <p class="winner-message">
                    {{ ucfirst($state['winner']) }} player wins.
                </p>
            @endif
        @else
            <p class="turn-message">
                Current turn: <strong class="player-{{ $state['currentPlayer'] }}">{{ ucfirst($state['currentPlayer']) }} player</strong>
            </p>
        @endif
    </div>

    <!-- Game Board -->
    <div class="board-container">
        <div class="game-board">
            @for($row = 0; $row < 6; $row++)
                @for($col = 0; $col < 7; $col++)
                    @php
                        $piece = $state['board'][$row][$col];
                        $isWinning = $this->isWinningPiece($row, $col);
                    @endphp
                    <div 
                        class="cell"
                        wire:click="dropPiece({{ $col }})"
                        @class([
                            'clickable' => !$state['gameOver'] && $this->canDropInColumn($col),
                            'column-' . $col
                        ])
                    >
                        @if($piece)
                            <div class="piece piece-{{ $piece }} {{ $isWinning ? 'winning' : '' }}"></div>
                        @else
                            <div class="empty-slot"></div>
                        @endif
                    </div>
                @endfor
            @endfor
        </div>
        
        <!-- Column indicators for mobile/accessibility -->
        <div class="column-indicators">
            @for($col = 0; $col < 7; $col++)
                <button 
                    wire:click="dropPiece({{ $col }})"
                    @disabled($state['gameOver'] || !$this->canDropInColumn($col))
                    class="column-button {{ $this->canDropInColumn($col) && !$state['gameOver'] ? 'available' : '' }}"
                >
                    ↓
                </button>
            @endfor
        </div>
    </div>

    <!-- Game Controls -->
    <div class="game-controls">
        <button wire:click="newGame" class="new-game-button">
            New Game
        </button>
    </div>

    <!-- Game Stats -->
    <div class="game-stats">
        <p>Moves: {{ $state['moves'] }}</p>
        <p>Mode: Pass and Play</p>
    </div>

    <style>
        .connect4-game {
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

        .player-red {
            color: #ef4444;
        }

        .player-yellow {
            color: var(--color-star-yellow, #fff89a);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .board-container {
            margin-bottom: 1.5rem;
        }

        .game-board {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-template-rows: repeat(6, 1fr);
            gap: 8px;
            background: #001a33;
            padding: 1rem;
            border-radius: 12px;
            max-width: 560px;
            margin: 0 auto;
        }

        .cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
            position: relative;
        }

        .cell.clickable {
            cursor: pointer;
        }

        .cell.clickable:hover {
            background: rgba(255, 248, 154, 0.1);
            transform: scale(1.05);
        }

        .empty-slot {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.05);
        }

        .piece {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            animation: dropIn 0.3s ease-out;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .piece-red {
            background: #ef4444;
            border: 3px solid #dc2626;
        }

        .piece-yellow {
            background: var(--color-star-yellow, #fff89a);
            border: 3px solid #fbbf24;
        }

        .piece.winning {
            animation: winPulse 1s ease-in-out infinite;
            box-shadow: 0 0 20px currentColor;
        }

        @keyframes dropIn {
            from {
                transform: translateY(-400%) scale(0.5);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        @keyframes winPulse {
            0%, 100% { 
                transform: scale(1);
                filter: brightness(1);
            }
            50% { 
                transform: scale(1.15);
                filter: brightness(1.3);
            }
        }

        .column-indicators {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            max-width: 560px;
            margin: 1rem auto 0;
            padding: 0 1rem;
        }

        .column-button {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 248, 154, 0.2);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }

        .column-button.available {
            color: var(--color-star-yellow, #fff89a);
            border-color: rgba(255, 248, 154, 0.4);
        }

        .column-button.available:hover {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
            transform: translateY(-2px);
        }

        .column-button:disabled {
            cursor: not-allowed;
            opacity: 0.3;
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
                max-width: 100%;
                padding: 0.75rem;
                gap: 6px;
            }

            .column-indicators {
                max-width: 100%;
                gap: 6px;
            }

            .column-button {
                font-size: 1.2rem;
                padding: 0.4rem;
            }

            .game-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .game-board {
                gap: 4px;
                padding: 0.5rem;
            }

            .column-indicators {
                gap: 4px;
            }
        }
    </style>
</div>

