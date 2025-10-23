<div class="snake-game" 
     x-data="{ 
         showRules: false,
         gameLoop: null,
         startLoop() {
             if (this.gameLoop) clearInterval(this.gameLoop);
             this.gameLoop = setInterval(() => {
                 if (@this.gameStarted && !@this.gameOver && !@this.paused) {
                     @this.tick();
                 }
             }, @this.speed);
         },
         stopLoop() {
             if (this.gameLoop) {
                 clearInterval(this.gameLoop);
                 this.gameLoop = null;
             }
         }
     }"
     x-init="startLoop(); $watch('$wire.speed', () => startLoop())"
     @keydown.window.arrow-up.prevent="@this.changeDirection('up')"
     @keydown.window.arrow-down.prevent="@this.changeDirection('down')"
     @keydown.window.arrow-left.prevent="@this.changeDirection('left')"
     @keydown.window.arrow-right.prevent="@this.changeDirection('right')"
     @keydown.window.w.prevent="@this.changeDirection('up')"
     @keydown.window.s.prevent="@this.changeDirection('down')"
     @keydown.window.a.prevent="@this.changeDirection('left')"
     @keydown.window.d.prevent="@this.changeDirection('right')"
     @keydown.window.space.prevent="@this.togglePause()">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>Snake</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Use arrow keys or WASD to control the snake's direction</li>
            <li>Guide the snake to eat food (green apple) to grow longer</li>
            <li>Avoid hitting walls or the snake's own body</li>
            <li>Game speeds up every 5 food items eaten</li>
            <li>Press SPACE to pause/unpause the game</li>
        </ul>
    </div>

    <!-- Score Display -->
    <div class="score-display">
        <div class="score-box">
            <div class="score-label">Score</div>
            <div class="score-value">{{ $score }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Level</div>
            <div class="score-value">{{ $level }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Best</div>
            <div class="score-value">{{ $highScore }}</div>
        </div>
        @if($gameStarted)
            <div class="score-box">
                <div class="score-label">Moves</div>
                <div class="score-value">{{ $moveCount }}</div>
            </div>
            <div class="score-box">
                <div class="score-label">Time</div>
                <div class="score-value">
                    @php
                        $elapsed = $this->getElapsedTime();
                        $minutes = floor($elapsed / 60);
                        $seconds = $elapsed % 60;
                        echo sprintf('%d:%02d', $minutes, $seconds);
                    @endphp
                </div>
            </div>
        @endif
    </div>

    <!-- Game Status -->
    @if(!$gameStarted)
        <div class="start-message">
            <button wire:click="startGame" class="start-button">
                Start
            </button>
            <p>Use arrow keys or WASD to move!</p>
        </div>
    @elseif($gameOver)
        <div class="game-message game-over-message">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-400" />
                <p>Game Over!</p>
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-400" />
            </div>
            <div class="space-y-2 text-sm">
                <p class="subtitle">Final Score: {{ $score }}</p>
                <p class="subtitle">Snake Length: {{ count($snake) }}</p>
                <p class="subtitle">Level Reached: {{ $level }}</p>
                <p class="subtitle">Moves: {{ $moveCount }}</p>
                <p class="subtitle">Time:
                    @php
                        $elapsed = $this->getElapsedTime();
                        $minutes = floor($elapsed / 60);
                        $seconds = $elapsed % 60;
                        echo sprintf('%d:%02d', $minutes, $seconds);
                    @endphp
                </p>
            </div>
        </div>
    @elseif($paused)
        <div class="game-message pause-message">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-pause class="w-5 h-5 text-ink" />
                <p>Paused</p>
            </div>
            <p class="subtitle">Press SPACE to resume</p>
        </div>
    @endif

    <!-- Game Board -->
    <div class="board-container">
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

    <!-- Game Controls -->
    <div class="game-controls-snake">
        <button wire:click="newGame"
                class="control-btn new-game"
                aria-label="Start new game">
            <x-heroicon-o-arrow-path class="w-4 h-4" />
            <span>New</span>
        </button>

        @if($gameStarted && !$gameOver)
            <button wire:click="togglePause"
                    class="control-btn"
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

        <div class="mobile-controls">
            <div class="control-grid">
                <div></div>
                <button @click="@this.changeDirection('up')" class="arrow-btn">↑</button>
                <div></div>
                <button @click="@this.changeDirection('left')" class="arrow-btn">←</button>
                <div></div>
                <button @click="@this.changeDirection('right')" class="arrow-btn">→</button>
                <div></div>
                <button @click="@this.changeDirection('down')" class="arrow-btn">↓</button>
                <div></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    Livewire.on('game-completed', (event) => {
        // Add celebration effect
        const gameContainer = document.querySelector('.snake-game');
        if (gameContainer) {
            gameContainer.classList.add('celebration');
            setTimeout(() => {
                gameContainer.classList.remove('celebration');
            }, 2000);
        }
    });
});
</script>
@endpush
