<x-ui.game-wrapper
    title="Galaxy Mapper"
    :rules="[
        'Left-click to map sectors - numbers reveal nearby black holes',
        'Right-click to place navigation markers on suspected singularities',
        'Chart the entire galaxy without falling into black holes to succeed',
        'Study the stellar patterns to predict cosmic hazards'
    ]"
    x-data="{ showCelebration: false, celebrationTimer: null }"
    x-init="
        $wire.on('game-completed', (event) => {
            showCelebration = true;
            clearTimeout(celebrationTimer);
            celebrationTimer = setTimeout(() => {
                showCelebration = false;
            }, 3000);
        });
    "
>

    {{-- Difficulty Selection --}}
    @if($showDifficultySelector && !$gameStarted)
        <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 mb-8">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-ink mb-2">Choose Difficulty</h3>
                <p class="text-sm text-ink/70">Select your challenge level</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <button wire:click="selectDifficulty('beginner')"
                        class="p-4 rounded-xl border-2 transition-all duration-200 hover:border-star hover:bg-star/10 hover:shadow-lg {{ $difficulty === 'beginner' ? 'border-star bg-star/10 text-star shadow-lg shadow-star/20' : 'border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.05)] text-ink' }}">
                    <div class="font-semibold">Nebula</div>
                    <div class="text-sm opacity-75">9×9 (10 black holes)</div>
                </button>
                <button wire:click="selectDifficulty('intermediate')"
                        class="p-4 rounded-xl border-2 transition-all duration-200 hover:border-star hover:bg-star/10 hover:shadow-lg {{ $difficulty === 'intermediate' ? 'border-star bg-star/10 text-star shadow-lg shadow-star/20' : 'border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.05)] text-ink' }}">
                    <div class="font-semibold">Galaxy</div>
                    <div class="text-sm opacity-75">16×16 (40 black holes)</div>
                </button>
                <button wire:click="selectDifficulty('expert')"
                        class="p-4 rounded-xl border-2 transition-all duration-200 hover:border-star hover:bg-star/10 hover:shadow-lg {{ $difficulty === 'expert' ? 'border-star bg-star/10 text-star shadow-lg shadow-star/20' : 'border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.05)] text-ink' }}">
                    <div class="font-semibold">Universe</div>
                    <div class="text-sm opacity-75">30×16 (99 black holes)</div>
                </button>
            </div>
        </div>
    @endif

    {{-- Game Status --}}
    @if($gameWon || $gameOver)
        <div class="glass rounded-xl border p-6 text-center mb-8 {{ $gameWon ? 'border-star/40 bg-star/5' : 'border-constellation/40 bg-constellation/5' }}">
            @if($gameWon)
                <div class="space-y-3">
                    <div class="flex items-center justify-center gap-3">
                        <x-heroicon-o-star class="w-6 h-6 text-star animate-pulse" />
                        <p class="text-xl font-semibold text-star">Victory! Galaxy mapped successfully.</p>
                        <x-heroicon-o-star class="w-6 h-6 text-star animate-pulse" style="animation-delay: 0.5s" />
                    </div>
                    <div class="flex items-center justify-center gap-6 text-sm text-ink/70">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-cursor-arrow-rays class="w-4 h-4" />
                            <span>{{ $moveCount }} moves</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-trophy class="w-4 h-4" />
                            <span>{{ $score }} points</span>
                        </div>
                        @if($squaresRevealed > 0)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-square-3-stack-3d class="w-4 h-4" />
                                <span>{{ $squaresRevealed }} sectors mapped</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    <div class="flex items-center justify-center gap-3">
                        <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-constellation animate-pulse" />
                        <p class="text-xl font-semibold text-constellation">Black hole singularity! Mission failed.</p>
                        <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-constellation animate-pulse" style="animation-delay: 0.5s" />
                    </div>
                    <div class="flex items-center justify-center gap-6 text-sm text-ink/70">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-cursor-arrow-rays class="w-4 h-4" />
                            <span>{{ $moveCount }} moves</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="w-4 h-4" />
                            <span>{{ $score }} points</span>
                        </div>
                        @if($squaresRevealed > 0)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span>{{ $squaresRevealed }} safe sectors found</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center mb-6">
            <div class="flex items-center justify-center gap-6 text-sm text-ink/70 mb-2">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-exclamation-triangle class="w-4 h-4" />
                    <span>{{ $mineCount - $flagsUsed }} black holes detected</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-heroicon-o-flag class="w-4 h-4" />
                    <span>{{ $flagsUsed }} markers placed</span>
                </div>
                @if($squaresRevealed > 0)
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        <span>{{ $squaresRevealed }} sectors mapped</span>
                    </div>
                @endif
            </div>
            @if($moveCount > 0)
                <div class="flex items-center justify-center gap-4 text-xs text-ink/50">
                    <span>{{ $moveCount }} moves</span>
                    @if($startTime)
                        <span>{{ $this->getElapsedTime() }}s elapsed</span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Game Board --}}
    <div class="minesweeper-board-container mb-8">
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
                         @contextmenu.prevent="$wire.flagCell({{ $cell['x'] }}, {{ $cell['y'] }})"
                         role="button"
                         :aria-label="$cell['revealed'] ? 'Cell ' . ($cell['number'] ?: 'mine') . ' revealed' : 'Hidden cell'"
                         tabindex="0">

                        @if($cell['flagged'])
                            <x-heroicon-o-flag class="w-4 h-4 text-star" />
                        @elseif($cell['revealed'])
                            @if($cell['type'] === 'mine')
                                <div class="black-hole">
                                    <div class="black-hole-core"></div>
                                    <div class="black-hole-ring"></div>
                                </div>
                            @elseif($cell['number'] > 0)
                                <span class="number number-{{ $cell['number'] }}">{{ $cell['number'] }}</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- Game Controls --}}
    <div class="game-controls">
        <div class="control-buttons">
            <button wire:click="newGame"
                    class="control-btn new-game"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New Game</span>
            </button>

            @if($moveCount > 0)
                <button wire:click="resetGame"
                        class="control-btn reset"
                        aria-label="Reset current game">
                    <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                    <span>Reset</span>
                </button>
            @endif
        </div>
    </div>

</x-ui.game-wrapper>
