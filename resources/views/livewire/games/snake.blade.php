<x-ui.game-wrapper
    title="Snake"
    :rules="[
        'Use arrow keys or WASD to control the serpent\'s direction',
        'Guide the snake to consume cosmic fruit and grow longer',
        'Avoid colliding with walls or the serpent\'s own body',
        'Game accelerates every 5 fruits consumed',
        'Press SPACE to pause/unpause during play'
    ]">

    <div
        x-data="{
            gameLoop: null,
            startLoop() {
                if (this.gameLoop) clearInterval(this.gameLoop);
                this.gameLoop = setInterval(() => {
                    if ($wire.gameStarted && !$wire.gameOver && !$wire.paused) {
                        $wire.tick();
                    }
                }, $wire.speed);
            },
            stopLoop() {
                if (this.gameLoop) {
                    clearInterval(this.gameLoop);
                    this.gameLoop = null;
                }
            }
        }"
        x-init="startLoop(); $watch('$wire.speed', () => startLoop())"
        @keydown.window.arrow-up.prevent="$wire.changeDirection('up')"
        @keydown.window.arrow-down.prevent="$wire.changeDirection('down')"
        @keydown.window.arrow-left.prevent="$wire.changeDirection('left')"
        @keydown.window.arrow-right.prevent="$wire.changeDirection('right')"
        @keydown.window.w.prevent="$wire.changeDirection('up')"
        @keydown.window.s.prevent="$wire.changeDirection('down')"
        @keydown.window.a.prevent="$wire.changeDirection('left')"
        @keydown.window.d.prevent="$wire.changeDirection('right')"
        @keydown.window.space.prevent="$wire.togglePause()"
    >
        {{-- Game Instructions --}}
        <div class="text-center mb-6">
        <p class="text-sm text-ink/60 mb-2">Use arrow keys or WASD to guide the serpent</p>
        <p class="text-xs text-ink/40">Consume cosmic fruit and avoid the void!</p>
        </div>

        {{-- Game Status and Score --}}
        @if($gameStarted)
        <div class="flex flex-wrap justify-center gap-4 text-sm">
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Score:</span>
                <strong class="text-star ml-2 font-bold">{{ number_format($score) }}</strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Level:</span>
                <strong class="text-constellation ml-2 font-bold">{{ $level }}</strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Best:</span>
                <strong class="text-ink ml-2 font-bold">{{ number_format($highScore) }}</strong>
            </div>
            @if($moveCount > 0)
                <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                    <span class="text-ink/60">Length:</span>
                    <strong class="text-ink ml-2 font-bold">{{ count($snake) }}</strong>
                </div>
            @endif
        </div>
        @endif

        {{-- Game Status Messages --}}
        @if(!$gameStarted)
        <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-play class="w-5 h-5 text-star" />
                <p class="text-lg font-semibold text-star">Ready to Hunt{{"?"}}</p>
                <x-heroicon-o-play class="w-5 h-5 text-star" />
            </div>
            <button wire:click="startGame"
                    class="px-8 py-3 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-1 hover:shadow-lg hover:shadow-star/20 transition-all inline-flex items-center gap-2">
                <x-heroicon-o-play class="w-5 h-5" />
                <span>Begin Journey</span>
            </button>
        </div>
        @elseif($gameOver)
        <div class="glass rounded-xl border border-[hsl(var(--border)/.3)] bg-[hsl(var(--space-900)/.5)] p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-ink/60" />
                <p class="text-lg font-semibold text-ink/80">Journey's End</p>
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-ink/60" />
            </div>
            <div class="space-y-1 text-sm text-ink/70">
                <div>Final Score: <strong class="text-star font-bold">{{ number_format($score) }}</strong></div>
                <div>Serpent Length: <strong class="text-constellation font-bold">{{ count($snake) }}</strong></div>
                <div>Level Reached: <strong class="text-ink font-bold">{{ $level }}</strong></div>
                <div>High Score: <strong class="text-ink font-bold">{{ number_format($highScore) }}</strong></div>
            </div>
        </div>
        @elseif($paused)
        <div class="glass rounded-xl border border-[hsl(var(--border)/.3)] bg-[hsl(var(--space-900)/.3)] p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-pause class="w-5 h-5 text-ink/60" />
                <p class="text-lg font-semibold text-ink/80">Paused</p>
                <x-heroicon-o-pause class="w-5 h-5 text-ink/60" />
            </div>
            <p class="text-sm text-ink/60">Press SPACE to continue your journey</p>
        </div>
        @endif

        {{-- Game Board --}}
        <div class="flex justify-center">
        <div class="snake-board">
            @for($y = 0; $y < 15; $y++)
                @for($x = 0; $x < 20; $x++)
                    @php
                        $isSnakeHead = !empty($snake) && $snake[0]['x'] === $x && $snake[0]['y'] === $y;
                        $isSnakeBody = false;
                        foreach(array_slice($snake, 1) as $segment) {
                            if ($segment['x'] === $x && $segment['y'] === $y) {
                                $isSnakeBody = true;
                                break;
                            }
                        }
                        $isFood = $food['x'] === $x && $food['y'] === $y;
                    @endphp

                    <div class="snake-cell
                                {{ $isSnakeHead ? 'snake-head' : '' }}
                                {{ $isSnakeBody ? 'snake-body' : '' }}
                                {{ $isFood ? 'food' : '' }}">
                    </div>
                @endfor
            @endfor
        </div>
        </div>

        {{-- Game Controls --}}
        <div class="space-y-4">
        <div class="flex flex-wrap justify-center gap-2">
            <button wire:click="newGame"
                    class="px-6 py-2 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20 transition-all inline-flex items-center gap-2"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New Journey</span>
            </button>

            @if($gameStarted && !$gameOver)
                <button wire:click="togglePause"
                        class="px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 {{ $paused ? 'bg-constellation/20 border-constellation text-constellation' : 'hover:border-star hover:bg-star/10' }} bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)]"
                        aria-label="{{ $paused ? 'Resume game' : 'Pause game' }}">
                    @if($paused)
                        <x-heroicon-o-play class="w-4 h-4" />
                        <span>Resume</span>
                    @else
                        <x-heroicon-o-pause class="w-4 h-4" />
                        <span>Pause</span>
                    @endif
                </button>
            @endif
        </div>

        {{-- Mobile Touch Controls --}}
        <div class="mobile-controls">
            <div class="grid grid-cols-3 gap-1 max-w-32 mx-auto">
                <div></div>
                <button @click="@this.changeDirection('up')" class="aspect-square rounded-lg border-2 border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.1)] text-ink hover:border-star/50 hover:bg-[hsl(var(--surface)/.2)] active:bg-star/10 transition-all text-sm font-bold min-h-[44px]" title="Move Up">↑</button>
                <div></div>
                <button @click="@this.changeDirection('left')" class="aspect-square rounded-lg border-2 border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.1)] text-ink hover:border-star/50 hover:bg-[hsl(var(--surface)/.2)] active:bg-star/10 transition-all text-sm font-bold min-h-[44px]" title="Move Left">←</button>
                <div></div>
                <button @click="@this.changeDirection('right')" class="aspect-square rounded-lg border-2 border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.1)] text-ink hover:border-star/50 hover:bg-[hsl(var(--surface)/.2)] active:bg-star/10 transition-all text-sm font-bold min-h-[44px]" title="Move Right">→</button>
                <div></div>
                <button @click="@this.changeDirection('down')" class="aspect-square rounded-lg border-2 border-[hsl(var(--border)/.3)] bg-[hsl(var(--surface)/.1)] text-ink hover:border-star/50 hover:bg-[hsl(var(--surface)/.2)] active:bg-star/10 transition-all text-sm font-bold min-h-[44px]" title="Move Down">↓</button>
                <div></div>
            </div>
            </div>
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
