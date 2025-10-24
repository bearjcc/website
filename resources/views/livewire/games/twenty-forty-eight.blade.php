<x-ui.game-wrapper
    title="2048"
    :rules="[
        'Use arrow keys or WASD to slide tiles in any direction',
        'When two tiles with the same number touch, they merge into one',
        'Reach the 2048 tile to win, or keep playing for a higher score',
        'Game ends when no more moves are possible',
        'Try to achieve the highest score possible!'
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
    @keydown.window.arrow-up.prevent="$wire.move('up')"
    @keydown.window.arrow-down.prevent="$wire.move('down')"
    @keydown.window.arrow-left.prevent="$wire.move('left')"
    @keydown.window.arrow-right.prevent="$wire.move('right')"
    @keydown.window.w.prevent="$wire.move('up')"
    @keydown.window.s.prevent="$wire.move('down')"
    @keydown.window.a.prevent="$wire.move('left')"
    @keydown.window.d.prevent="$wire.move('right')">
    {{-- Custom keyboard instructions --}}
    <div class="text-center mb-6">
        <p class="text-sm text-ink/60 mb-2">Use arrow keys or WASD to move tiles</p>
        <p class="text-xs text-ink/40">Combine matching numbers to reach 2048!</p>
    </div>

    {{-- Game Status and Score --}}
    @if($gameStarted)
        <div class="flex flex-wrap justify-center gap-4 text-sm">
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Score:</span>
                <strong class="text-star ml-2 font-bold">{{ number_format($score) }}</strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Best:</span>
                <strong class="text-ink ml-2 font-bold">{{ number_format($bestScore) }}</strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Moves:</span>
                <strong class="text-ink ml-2 font-bold">{{ $moveCount }}</strong>
            </div>
            @if($moveCount > 0)
                <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                    <span class="text-ink/60">Time:</span>
                    <strong class="text-ink ml-2 font-bold">
                        @php
                            $elapsed = $this->getElapsedTime();
                            $minutes = floor($elapsed / 60);
                            $seconds = $elapsed % 60;
                            echo sprintf('%d:%02d', $minutes, $seconds);
                        @endphp
                    </strong>
                </div>
            @endif
        </div>
    @endif

    {{-- Game Completion Messages --}}
    @if($isWon && !$isOver)
        <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                <p class="text-lg font-semibold text-star">2048 Achieved!</p>
                <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
            </div>
            <p class="text-sm text-ink/70">Keep playing to reach even higher scores!</p>
        </div>
    @elseif($isOver)
        <div class="glass rounded-xl border border-[hsl(var(--border)/.3)] bg-[hsl(var(--space-900)/.5)] p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-ink/60" />
                <p class="text-lg font-semibold text-ink/80">Game Over</p>
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-ink/60" />
            </div>
            <div class="space-y-1 text-sm text-ink/70">
                <div>Final Score: <strong class="text-star font-bold">{{ number_format($score) }}</strong></div>
                <div>Max Tile: <strong class="text-constellation font-bold">{{ number_format(max($board)) }}</strong></div>
                <div>Moves: <strong class="text-ink font-bold">{{ $moveCount }}</strong></div>
            </div>
        </div>
    @endif

    {{-- Game Board --}}
    <div class="flex justify-center">
        <div class="game-board-2048 relative interactive-glow smooth-transition">
            @foreach($board as $index => $value)
                @php
                    $row = intval($index / 4);
                    $col = $index % 4;
                @endphp
                <div class="tile-cell smooth-transition">
                    @if($value > 0)
                        <div class="tile tile-{{ $value }} smooth-transition"
                             style="background: {{ $this->getTileColor($value) }};
                                    color: {{ $this->getTileTextColor($value) }};
                                    box-shadow: 0 2px 8px {{ $this->getTileColor($value) }}20;">
                            {{ number_format($value) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Game Controls --}}
    <div class="space-y-4">
        <div class="flex flex-wrap justify-center gap-2">
            <button wire:click="newGame"
                    class="px-6 py-2 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20 transition-all inline-flex items-center gap-2 interactive-glow smooth-transition enhanced-focus"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New Game</span>
            </button>

            <button wire:click="undo"
                    class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 {{ empty($previousState) ? 'opacity-40 cursor-not-allowed' : 'hover:border-star hover:bg-star/10 interactive-glow' }} bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] smooth-transition enhanced-focus"
                    @disabled(empty($previousState))
                    aria-label="Undo last move">
                <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                <span>Undo</span>
            </button>
        </div>

        {{-- Instructions for mobile users --}}
        <div class="text-center">
            <p class="text-xs text-ink/60">
                <span class="hidden md:inline">Use arrow keys or WASD to move tiles</span>
                <span class="md:hidden">Swipe or tap to move tiles</span>
            </p>
        </div>
    </div>
</x-ui.game-wrapper>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    Livewire.on('game-completed', (event) => {
        // Add celebration effect to the game wrapper
        const gameSection = document.querySelector('.section');
        if (gameSection) {
            gameSection.classList.add('celebration');
            setTimeout(() => {
                gameSection.classList.remove('celebration');
            }, 2000);
        }
    });
});
</script>
@endpush
