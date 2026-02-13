<div class="section py-12 md:py-16" 
     x-data="{ 
         hoverCell: null,
         showNumberPicker: false,
         pickerRow: null,
         pickerCol: null,
         setHover(row, col) {
             if (@js($originalPuzzle)[row][col] === 0) {
                 this.hoverCell = [row, col];
                 this.showNumberPicker = true;
                 this.pickerRow = row;
                 this.pickerCol = col;
             }
         },
         clearHover() {
             this.hoverCell = null;
             this.showNumberPicker = false;
             this.pickerRow = null;
             this.pickerCol = null;
         }
     }">
    
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Back nav --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">Sudoku</h1>
        </div>

        {{-- Difficulty Selection --}}
        @if($showDifficultySelector && !$gameStarted)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 space-y-4">
                <h3 class="text-lg font-semibold text-ink">Select Difficulty</h3>
                
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Games\Sudoku\SudokuEngine::DIFFICULTIES as $key => $info)
                        <button wire:click="selectDifficulty('{{ $key }}')"
                                class="px-4 py-2 rounded-lg border transition-all bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star hover:bg-star/10 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20">
                            {{ $info['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-[hsl(var(--border)/.1)]">
                    <button wire:click="toggleCustomInput" 
                            class="px-4 py-2 rounded-lg border transition-all bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-constellation">
                        <x-heroicon-o-document-text class="w-4 h-4 inline" />
                        <span>Load Custom Puzzle</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Custom Puzzle Input --}}
        @if($showCustomInput)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 space-y-4">
                <h3 class="text-lg font-semibold text-ink">Enter Custom Puzzle</h3>
                <p class="text-sm text-ink/70">Enter 81 digits (0 for empty cells). Paste from newspaper or app.</p>
                
                <textarea 
                    wire:model="customPuzzleInput"
                    class="w-full h-32 px-4 py-3 bg-[hsl(var(--space-900))] border border-[hsl(var(--border)/.3)] rounded-lg text-ink font-mono text-sm focus:outline-none focus:border-star resize-none"
                    placeholder="Example: 530070000600195000098000060800060003400803001700020006060000280000419005000080079"
                ></textarea>

                <div class="flex gap-2">
                    <button wire:click="loadCustomPuzzle" 
                            class="px-6 py-2 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20 transition-all">
                        Load Puzzle
                    </button>
                    <button wire:click="toggleCustomInput" 
                            class="px-6 py-2 rounded-lg border border-[hsl(var(--border)/.3)] text-ink hover:border-ink transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        @endif

        {{-- Game Status --}}
        @if($gameStarted && !$showDifficultySelector)
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                    <span class="text-ink/60">Difficulty:</span>
                    <strong class="text-star ml-2">{{ ucfirst($difficulty) }}</strong>
                </div>
                <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                    <span class="text-ink/60">Hints:</span>
                    <strong class="text-ink ml-2">{{ $hintsUsed }}/{{ $maxHints }}</strong>
                </div>
                <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                    <span class="text-ink/60">Mistakes:</span>
                    <strong class="text-ink ml-2">{{ $mistakes }}/{{ $maxMistakes }}</strong>
                </div>
            </div>
        @endif

        {{-- Hint Explanation --}}
        @if($hintStep)
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-4 space-y-3">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-light-bulb class="w-5 h-5 text-star" />
                    <h3 class="text-lg font-semibold text-star">Hint Applied</h3>
                </div>
                <p class="text-sm text-ink/80">{{ $hintStep['explanation'] }}</p>
                <div class="text-xs text-ink/60">
                    <span class="font-medium text-star">Technique:</span> {{ $hintStep['technique_name'] ?? ucfirst(str_replace('_', ' ', $hintStep['technique'])) }}
                </div>
                @if(!empty($hintStep['placements']))
                    <div class="text-xs text-ink/60">
                        <span class="font-medium text-star">Placements:</span>
                        @foreach($hintStep['placements'] as $placement)
                            R{{ $placement['r'] + 1 }}C{{ $placement['c'] + 1 }} = {{ $placement['d'] }}
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Completion Message --}}
        @if($gameComplete)
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
                <div class="flex items-center justify-center gap-2">
                    <x-heroicon-o-star class="w-6 h-6 text-star animate-pulse" />
                    <p class="text-xl font-bold text-star">Congratulations!</p>
                    <x-heroicon-o-star class="w-6 h-6 text-star animate-pulse" style="animation-delay: 0.5s" />
                </div>
                <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
                    <span class="px-2 py-1 bg-[hsl(var(--surface)/.2)] rounded">{{ ucfirst($difficulty) }} Level</span>
                    <span class="w-1 h-1 rounded-full bg-ink/40"></span>
                    <span>{{ $hintsUsed }} hints used</span>
                    <span class="w-1 h-1 rounded-full bg-ink/40"></span>
                    <span>{{ $mistakes }} mistakes made</span>
                </div>
                <div class="text-xs text-ink/60 mt-2">
                    @php
                        $score = 1000 - ($hintsUsed * 50) - ($mistakes * 25);
                        $score = max(100, $score);
                    @endphp
                    Performance Score: <span class="font-semibold text-star">{{ $score }}</span>
                </div>
            </div>
        @endif

        {{-- Sudoku Board --}}
        @if($gameStarted || !$showDifficultySelector)
            <div class="sudoku-board-container">
                <div class="sudoku-board">
                    @for($row = 0; $row < 9; $row++)
                        @for($col = 0; $col < 9; $col++)
                            @php
                                $value = $board[$row][$col];
                                $isOriginal = $originalPuzzle[$row][$col] !== 0;
                                $isSelected = $selectedCell && $selectedCell[0] === $row && $selectedCell[1] === $col;
                                $isConflict = $this->isConflict($row, $col);
                                $isHint = $this->isLastHint($row, $col);
                                $isUserInput = !$isOriginal && $value !== 0;
                                $cellNotes = $notes[$row][$col] ?? [];
                            @endphp

                            <div class="sudoku-cell
                                        {{ $isOriginal ? 'original' : '' }}
                                        {{ $isSelected ? 'selected' : '' }}
                                        {{ $isConflict ? 'conflict' : '' }}
                                        {{ $isHint ? 'hint' : '' }}
                                        {{ $isUserInput ? 'user-input' : '' }}"
                                 @if(!$isOriginal)
                                     @mouseenter="setHover({{ $row }}, {{ $col }})"
                                     @mouseleave="clearHover()"
                                 @endif
                                 wire:click="selectCell({{ $row }}, {{ $col }})"
                                 tabindex="0"
                                 aria-label="Cell {{ $row + 1 }}, {{ $col + 1 }}{{ $value ? ', value ' . $value : ', empty' }}">
                                
                                @if($value !== 0)
                                    <span class="cell-number">{{ $value }}</span>
                                @else
                                    {{-- Remaining numbers display (when 2+ eliminated) --}}
                                    @php
                                        $remainingNumbers = $this->getRemainingNumbers($row, $col);
                                        $hasRemainingNumbers = $this->hasRemainingNumbers($row, $col);
                                    @endphp
                                    @if($hasRemainingNumbers && count($remainingNumbers) <= 7)
                                        <div class="remaining-numbers">
                                            {{ implode('', $remainingNumbers) }}
                                        </div>
                                    @endif

                                    {{-- Notes display --}}
                                    @if(!empty($cellNotes))
                                        <div class="cell-notes">
                                            @for($n = 1; $n <= 9; $n++)
                                                <span class="{{ in_array($n, $cellNotes) ? 'note-active' : 'note-empty' }}">
                                                    {{ in_array($n, $cellNotes) ? $n : '' }}
                                                </span>
                                            @endfor
                                        </div>
                                    @endif
                                @endif

                                {{-- Hover Number Picker --}}
                                @if(!$isOriginal)
                                    <div x-show="showNumberPicker && pickerRow === {{ $row }} && pickerCol === {{ $col }}"
                                         x-transition.opacity.duration.200ms
                                         @click.stop
                                         class="number-picker">
                                        @for($n = 1; $n <= 9; $n++)
                                            @php
                                                $isEliminated = in_array($n, $this->eliminations[$row][$col] ?? []);
                                            @endphp
                                            <button class="picker-number {{ $isEliminated ? 'eliminated' : '' }}"
                                                    @click.left="$wire.placeNumberAt({{ $row }}, {{ $col }}, {{ $n }}); clearHover()"
                                                    @click.right.prevent="$wire.toggleEliminationAt({{ $row }}, {{ $col }}, {{ $n }}); $event.stopPropagation()"
                                                    @contextmenu.prevent
                                                    aria-label="{{ $isEliminated ? 'Un-eliminate' : 'Eliminate' }} {{ $n }}">
                                                {{ $n }}
                                            </button>
                                        @endfor
                                    </div>
                                @endif
                            </div>
                        @endfor
                    @endfor
                </div>
            </div>
        @endif

        {{-- Controls --}}
        @if($gameStarted && !$gameComplete)
            <div class="space-y-4">
                <div class="flex flex-wrap justify-center gap-2">
                    <button wire:click="useHint"
                            @disabled($hintsUsed >= $maxHints)
                            class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 {{ $hintsUsed >= $maxHints ? 'opacity-40 cursor-not-allowed' : 'hover:border-star hover:bg-star/10' }} bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)]"
                            aria-label="Use hint ({{ $maxHints - $hintsUsed }} remaining)">
                        <x-heroicon-o-light-bulb class="w-4 h-4" />
                        <span>Hint</span>
                    </button>

                    <button wire:click="toggleNotesMode"
                            class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 {{ $notesMode ? 'bg-star/20 border-star' : 'bg-[hsl(var(--surface)/.1)] border-[hsl(var(--border)/.3)] hover:border-star' }} text-ink"
                            aria-label="Toggle notes mode">
                        <x-heroicon-o-pencil class="w-4 h-4" />
                        <span>Notes</span>
                    </button>

                    <button wire:click="clearCell"
                            class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star"
                            aria-label="Clear selected cell">
                        <x-heroicon-o-backspace class="w-4 h-4" />
                        <span>Clear</span>
                    </button>

                    <button wire:click="autoSolve"
                            class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-constellation"
                            aria-label="Auto-solve puzzle">
                        <x-heroicon-o-bolt class="w-4 h-4" />
                        <span>Solve</span>
                    </button>
                </div>

                <div class="flex justify-center">
                    <button wire:click="newGame"
                            class="px-6 py-2 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20 transition-all"
                            aria-label="Start new game">
                        <x-heroicon-o-arrow-path class="w-4 h-4 inline" />
                        <span>New Game</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

