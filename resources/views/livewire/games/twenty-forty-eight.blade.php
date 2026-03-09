<div
    x-data="{
        showCelebration: false,
        celebrationTimer: null,
        touchStartX: null,
        touchStartY: null,
        swipeThreshold: 24,
        handleTouchStart(event) {
            const touch = event.changedTouches[0];
            this.touchStartX = touch.clientX;
            this.touchStartY = touch.clientY;
        },
        handleTouchEnd(event) {
            if (this.touchStartX === null || this.touchStartY === null) {
                return;
            }

            const touch = event.changedTouches[0];
            const deltaX = touch.clientX - this.touchStartX;
            const deltaY = touch.clientY - this.touchStartY;
            const absX = Math.abs(deltaX);
            const absY = Math.abs(deltaY);

            if (Math.max(absX, absY) < this.swipeThreshold) {
                this.touchStartX = null;
                this.touchStartY = null;

                return;
            }

            if (absX > absY) {
                $wire.move(deltaX > 0 ? 'right' : 'left');
            } else {
                $wire.move(deltaY > 0 ? 'down' : 'up');
            }

            this.touchStartX = null;
            this.touchStartY = null;
        }
    }"
    x-init="
        $wire.on('game-completed', () => {
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
    @keydown.window.d.prevent="$wire.move('right')"
>
    <x-ui.game-chrome
        :gameTitle="$game->title"
        :gamePageUrl="route('games.show', $game->slug)"
        :rightContent="$this->chromeSummary"
    />

    <x-ui.play-main>
        <p class="text-center text-sm text-ink/80">Slide tiles to combine. Reach 2048.</p>

        @if($isWon && !$isOver)
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-4 text-center space-y-2">
                <p class="text-base font-semibold text-star">2048 achieved.</p>
                <p class="text-sm text-ink/70">Keep going if you want a higher score.</p>
            </div>
        @elseif($isOver)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.3)] bg-[hsl(var(--space-900)/.5)] p-4 text-center space-y-2">
                <p class="text-base font-semibold text-ink/90">No more moves.</p>
                <p class="text-sm text-ink/70">Start a new board when you are ready.</p>
            </div>
        @endif

        <div class="flex justify-center">
            <div
                class="game-board-2048 relative touch-none"
                role="application"
                aria-label="2048 board"
                @touchstart.passive="handleTouchStart($event)"
                @touchend.passive="handleTouchEnd($event)"
            >
                @foreach($board as $value)
                    <div class="tile-cell smooth-transition">
                        @if($value > 0)
                            <div
                                class="tile tile-{{ $value }} smooth-transition"
                                style="background: {{ $this->getTileColor($value) }}; color: {{ $this->getTileTextColor($value) }}; box-shadow: 0 2px 8px {{ $this->getTileColor($value) }}20;"
                            >
                                {{ number_format($value) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @php
            $bubbles = [
                ['label' => 'Score', 'value' => number_format($score), 'highlight' => $score > 0],
                ['label' => 'Best', 'value' => number_format($bestScore)],
                ['label' => 'Tile', 'value' => number_format($this->maxTile), 'highlight' => $this->maxTile >= 128],
                ['label' => 'Moves', 'value' => (string) $moveCount],
            ];

            if ($gameStarted) {
                $bubbles[] = ['label' => 'Time', 'value' => $this->elapsedClock];
            }
        @endphp
        <x-ui.info-bubbles :bubbles="$bubbles" />

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <button type="button" wire:click="newGame" class="btn-primary">New game</button>
            <button
                type="button"
                wire:click="undo"
                class="btn-secondary"
                @disabled(!$this->canUndo)
                aria-label="Undo last move"
            >
                Undo
            </button>
        </div>

        <div class="flex justify-center">
            <div class="grid grid-cols-3 gap-2" aria-label="Direction controls">
                <span aria-hidden="true"></span>
                <button type="button" wire:click="move('up')" class="btn-secondary h-11 w-11 p-0" aria-label="Move tiles up">
                    <x-heroicon-o-chevron-up class="mx-auto h-5 w-5" />
                </button>
                <span aria-hidden="true"></span>
                <button type="button" wire:click="move('left')" class="btn-secondary h-11 w-11 p-0" aria-label="Move tiles left">
                    <x-heroicon-o-chevron-left class="mx-auto h-5 w-5" />
                </button>
                <button type="button" wire:click="move('down')" class="btn-secondary h-11 w-11 p-0" aria-label="Move tiles down">
                    <x-heroicon-o-chevron-down class="mx-auto h-5 w-5" />
                </button>
                <button type="button" wire:click="move('right')" class="btn-secondary h-11 w-11 p-0" aria-label="Move tiles right">
                    <x-heroicon-o-chevron-right class="mx-auto h-5 w-5" />
                </button>
            </div>
        </div>

        <p class="text-center text-xs text-ink/50 pt-1">
            <span class="hidden md:inline">Use arrow keys, WASD, swipe, or the direction pad.</span>
            <span class="md:hidden">Swipe the board or use the direction pad.</span>
        </p>
    </x-ui.play-main>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    Livewire.on('game-completed', () => {
        const gameSection = document.querySelector('.game-board-2048');
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
