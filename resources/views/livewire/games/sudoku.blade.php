<div class="sudoku-game" x-data="{ showRules: false, hintPulsing: false }" 
     @hint-used.window="hintPulsing = true; setTimeout(() => hintPulsing = false, 2000)">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>Sudoku</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Fill the 9×9 grid so each row, column, and 3×3 box contains digits 1-9 exactly once</li>
            <li>Click a cell to select it, then click a number (1-9) or use keyboard</li>
            <li>Toggle Notes mode to mark possible candidates in cells</li>
            <li>Use hints when stuck (limited based on difficulty)</li>
            <li>Invalid entries will be highlighted in red</li>
        </ul>
    </div>

    <!-- Difficulty Selection -->
    @if($showDifficultySelector && !$gameStarted)
        <div class="difficulty-selection">
            <h3>Select Difficulty</h3>
            <div class="difficulty-buttons">
                <button wire:click="selectDifficulty('beginner')" class="difficulty-btn">
                    Beginner<br><small>45 clues</small>
                </button>
                <button wire:click="selectDifficulty('easy')" class="difficulty-btn">
                    Easy<br><small>38 clues</small>
                </button>
                <button wire:click="selectDifficulty('medium')" class="difficulty-btn">
                    Medium<br><small>30 clues</small>
                </button>
                <button wire:click="selectDifficulty('hard')" class="difficulty-btn">
                    Hard<br><small>24 clues</small>
                </button>
                <button wire:click="selectDifficulty('expert')" class="difficulty-btn">
                    Expert<br><small>18 clues</small>
                </button>
            </div>
        </div>
    @endif

    <!-- Game Status -->
    <div class="game-status">
        @if($gameComplete)
            <p class="winner-message">🎉 Puzzle Complete! 🎉</p>
        @else
            <div class="status-grid">
                <div class="status-item">
                    <span class="label">Difficulty:</span>
                    <span class="value">{{ ucfirst($difficulty) }}</span>
                </div>
                <div class="status-item">
                    <span class="label">Hints:</span>
                    <span class="value">{{ $hintsUsed }}/{{ $maxHints }}</span>
                </div>
                <div class="status-item">
                    <span class="label">Mistakes:</span>
                    <span class="value {{ $mistakes > 0 ? 'text-warning' : '' }}">{{ $mistakes }}/{{ $maxMistakes }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Sudoku Board -->
    <div class="board-container">
        <div class="sudoku-board">
            @for($row = 0; $row < 9; $row++)
                @for($col = 0; $col < 9; $col++)
                    @php
                        $number = $board[$row][$col];
                        $isOriginal = $originalPuzzle[$row][$col] !== 0;
                        $isSelected = $selectedCell && $selectedCell[0] === $row && $selectedCell[1] === $col;
                        $isConflict = $this->isConflict($row, $col);
                        $isHint = $this->isLastHint($row, $col);
                        $cellNotes = $notes[$row][$col] ?? [];
                        $boxBorder = '';
                        if ($row % 3 === 2 && $row !== 8) $boxBorder .= ' border-bottom-thick';
                        if ($col % 3 === 2 && $col !== 8) $boxBorder .= ' border-right-thick';
                    @endphp
                    
                    <button
                        wire:click="selectCell({{ $row }}, {{ $col }})"
                        class="sudoku-cell {{ $isOriginal ? 'original' : 'editable' }} 
                               {{ $isSelected ? 'selected' : '' }}
                               {{ $isConflict ? 'conflict' : '' }}
                               {{ $isHint ? 'hint-cell' : '' }}
                               {{ $boxBorder }}"
                        @disabled($gameComplete)
                    >
                        @if($number > 0)
                            <span class="cell-number {{ $isOriginal ? 'original-number' : 'player-number' }}">
                                {{ $number }}
                            </span>
                        @elseif(!empty($cellNotes))
                            <div class="cell-notes">
                                @for($n = 1; $n <= 9; $n++)
                                    <span class="note {{ in_array($n, $cellNotes) ? 'active' : 'empty' }}">
                                        {{ in_array($n, $cellNotes) ? $n : '' }}
                                    </span>
                                @endfor
                            </div>
                        @endif
                    </button>
                @endfor
            @endfor
        </div>
    </div>

    <!-- Number Input -->
    @if($selectedCell && !$gameComplete)
        <div class="number-input">
            <div class="number-buttons">
                @for($num = 1; $num <= 9; $num++)
                    <button wire:click="placeNumber({{ $num }})" class="number-btn">
                        {{ $num }}
                    </button>
                @endfor
            </div>
        </div>
    @endif

    <!-- Game Controls -->
    <div class="game-controls">
        <div class="control-buttons">
            <button wire:click="toggleNotesMode" 
                    class="control-btn {{ $notesMode ? 'active' : '' }}"
                    title="Toggle Notes Mode">
                {{ $notesMode ? '✏️ Notes On' : '✏️ Notes Off' }}
            </button>
            
            <button wire:click="clearCell" 
                    class="control-btn"
                    @disabled(!$selectedCell || $gameComplete)
                    title="Clear Selected Cell">
                🗑️ Clear
            </button>
            
            <button wire:click="useHint" 
                    class="control-btn"
                    @disabled($hintsUsed >= $maxHints || $gameComplete)
                    title="Use Hint ({{ $maxHints - $hintsUsed }} remaining)">
                💡 Hint
            </button>
            
            <button wire:click="newGame" 
                    class="control-btn new-game"
                    title="Start New Game">
                🔄 New Game
            </button>
        </div>
    </div>

    <style>
        .sudoku-game {
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
            min-width: 100px;
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
            min-height: 3rem;
        }

        .winner-message {
            color: var(--color-star-yellow, #fff89a);
            font-size: 1.5rem;
            font-weight: bold;
            animation: pulse 1s ease-in-out infinite;
        }

        .status-grid {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .status-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .status-item .label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .status-item .value {
            font-size: 1.2rem;
            color: var(--color-star-yellow, #fff89a);
            font-weight: bold;
        }

        .text-warning {
            color: #ff6b6b !important;
        }

        .board-container {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }

        .sudoku-board {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 1px;
            background: var(--color-star-yellow, #fff89a);
            border: 3px solid var(--color-star-yellow, #fff89a);
            max-width: 500px;
            width: 100%;
            aspect-ratio: 1;
        }

        .sudoku-cell {
            background: rgba(0, 0, 0, 0.6);
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-size: 1.5rem;
            font-weight: bold;
            min-height: 45px;
        }

        .sudoku-cell:not(.original):hover {
            background: rgba(255, 248, 154, 0.2);
        }

        .sudoku-cell.selected {
            background: rgba(255, 248, 154, 0.3) !important;
            box-shadow: inset 0 0 0 2px var(--color-star-yellow, #fff89a);
        }

        .sudoku-cell.original {
            background: rgba(0, 26, 51, 0.8);
        }

        .sudoku-cell.conflict {
            background: rgba(255, 107, 107, 0.3) !important;
        }

        .sudoku-cell.hint-cell {
            background: rgba(78, 205, 196, 0.3) !important;
            animation: hint-pulse 0.5s ease-in-out 3;
        }

        .border-right-thick {
            border-right: 2px solid var(--color-star-yellow, #fff89a) !important;
        }

        .border-bottom-thick {
            border-bottom: 2px solid var(--color-star-yellow, #fff89a) !important;
        }

        .cell-number {
            font-size: 1.8rem;
        }

        .original-number {
            color: white;
            font-weight: 700;
        }

        .player-number {
            color: var(--color-star-yellow, #fff89a);
            font-weight: 600;
        }

        .cell-notes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 1px;
            width: 100%;
            height: 100%;
            font-size: 0.6rem;
            padding: 2px;
        }

        .note {
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 248, 154, 0.6);
        }

        .note.empty {
            visibility: hidden;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes hint-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .number-input {
            margin-bottom: 1.5rem;
        }

        .number-buttons {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 0.5rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .number-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .number-btn:hover {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            transform: translateY(-2px);
        }

        .game-controls {
            margin-top: 2rem;
        }

        .control-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .control-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 248, 154, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .control-btn:hover:not(:disabled) {
            background: rgba(255, 248, 154, 0.2);
            border-color: var(--color-star-yellow, #fff89a);
        }

        .control-btn.active {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            border-color: var(--color-star-yellow, #fff89a);
        }

        .control-btn.new-game {
            background: var(--color-star-yellow, #fff89a);
            color: #000;
            font-weight: bold;
        }

        .control-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .sudoku-board {
                max-width: 350px;
            }

            .cell-number {
                font-size: 1.2rem;
            }

            .number-buttons {
                grid-template-columns: repeat(9, 1fr);
                gap: 0.25rem;
            }

            .number-btn {
                padding: 0.5rem;
                font-size: 1rem;
            }

            .control-buttons {
                flex-direction: column;
            }

            .control-btn {
                width: 100%;
            }
        }
    </style>
</div>
