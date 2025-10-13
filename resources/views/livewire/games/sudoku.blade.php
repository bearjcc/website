<div class="section py-12 md:py-16" x-data="{ hintPulsing: false }" 
     @hint-used.window="hintPulsing = true; setTimeout(() => hintPulsing = false, 2000)">
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Back nav --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('games.index') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">Sudoku</h1>
        </div>

        {{-- Rules --}}
        <details class="glass rounded-xl border border-[hsl(var(--border)/.1)] overflow-hidden">
            <summary class="px-6 py-3 cursor-pointer text-ink/80 hover:text-ink hover:bg-[hsl(var(--surface)/.08)] transition-colors list-none flex items-center justify-between">
                <span>Rules</span>
                <x-heroicon-o-chevron-down class="w-5 h-5" />
            </summary>
            <div class="px-6 py-4 border-t border-[hsl(var(--border)/.1)] space-y-2 text-ink/70 text-sm">
                <p>Fill the 9×9 grid so each row, column, and 3×3 box contains digits 1-9 exactly once.</p>
                <p>Click a cell, then a number. Toggle Notes mode for candidates. Use hints when stuck.</p>
            </div>
        </details>

        {{-- Difficulty Selection --}}
        @if($showDifficultySelector && !$gameStarted)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 space-y-4">
                <h3 class="text-lg font-semibold text-ink">Difficulty</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(['beginner' => 45, 'easy' => 38, 'medium' => 30, 'hard' => 24, 'expert' => 18] as $level => $clues)
                        <button wire:click="selectDifficulty('{{ $level }}')" 
                                class="px-4 py-2 rounded-lg border transition-all bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star">
                            <div class="font-semibold">{{ ucfirst($level) }}</div>
                            <div class="text-xs text-ink/60">{{ $clues }} clues</div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Game Status --}}
        @if($gameComplete)
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-4 text-center">
                <p class="text-lg font-semibold text-star">Puzzle complete.</p>
            </div>
        @else
            <div class="flex justify-center gap-8 text-sm text-ink/70">
                <div><span class="text-ink/50">Difficulty:</span> <strong class="text-ink">{{ ucfirst($difficulty) }}</strong></div>
                <div><span class="text-ink/50">Hints:</span> <strong class="text-ink">{{ $hintsUsed }}/{{ $maxHints }}</strong></div>
                <div><span class="text-ink/50">Mistakes:</span> <strong class="{{ $mistakes > 0 ? 'text-star' : 'text-ink' }}">{{ $mistakes }}/{{ $maxMistakes }}</strong></div>
            </div>
        @endif

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
</div>
