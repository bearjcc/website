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
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
                <div class="flex items-center justify-center gap-2">
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                    <p class="text-lg font-semibold text-star">Puzzle complete.</p>
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
                </div>
                <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
                    <span>{{ ucfirst($difficulty) }}</span>
                    <span class="w-1 h-1 rounded-full bg-ink/40"></span>
                    <span>{{ $hintsUsed }} hints</span>
                    <span class="w-1 h-1 rounded-full bg-ink/40"></span>
                    <span>{{ $mistakes }} mistakes</span>
                </div>
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
                    aria-label="Toggle notes mode">
                <x-heroicon-o-pencil class="w-4 h-4" />
                <span>{{ $notesMode ? 'Notes On' : 'Notes' }}</span>
            </button>
            
            <button wire:click="clearCell" 
                    class="control-btn"
                    @disabled(!$selectedCell || $gameComplete)
                    aria-label="Clear selected cell">
                <x-heroicon-o-backspace class="w-4 h-4" />
                <span>Clear</span>
            </button>
            
            <button wire:click="useHint" 
                    class="control-btn"
                    @disabled($hintsUsed >= $maxHints || $gameComplete)
                    aria-label="Use hint - {{ $maxHints - $hintsUsed }} remaining">
                <x-heroicon-o-light-bulb class="w-4 h-4" />
                <span>Hint</span>
            </button>
            
            <button wire:click="newGame" 
                    class="control-btn new-game"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New</span>
            </button>
        </div>
    </div>

</div>
