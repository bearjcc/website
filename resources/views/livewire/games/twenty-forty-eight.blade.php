<div class="game-2048" 
     x-data="{ showRules: false }"
     @keydown.window.arrow-up.prevent="$wire.move('up')"
     @keydown.window.arrow-down.prevent="$wire.move('down')"
     @keydown.window.arrow-left.prevent="$wire.move('left')"
     @keydown.window.arrow-right.prevent="$wire.move('right')"
     @keydown.window.w.prevent="$wire.move('up')"
     @keydown.window.s.prevent="$wire.move('down')"
     @keydown.window.a.prevent="$wire.move('left')"
     @keydown.window.d.prevent="$wire.move('right')">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>2048</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Use arrow keys (↑ ↓ ← →) or WASD to slide tiles</li>
            <li>When two tiles with the same number touch, they merge into one</li>
            <li>Reach the 2048 tile to win!</li>
            <li>Game is over when no moves are possible</li>
            <li>Try to get the highest score!</li>
        </ul>
        <p><strong>Controls:</strong></p>
        <ul>
            <li><strong>Arrow Keys</strong> or <strong>WASD</strong> - Move tiles</li>
            <li><strong>New Game button</strong> - Start fresh</li>
            <li><strong>Undo button</strong> - Take back last move (one only)</li>
        </ul>
    </div>

    <!-- Score Display -->
    <div class="score-display">
        <div class="score-box">
            <div class="score-label">Score</div>
            <div class="score-value">{{ number_format($score) }}</div>
        </div>
        <div class="score-box">
            <div class="score-label">Best</div>
            <div class="score-value">{{ number_format($bestScore) }}</div>
        </div>
    </div>

    <!-- Game Status -->
    @if($isWon && !$isOver)
        <div class="game-message win-message">
            <p>You reached 2048.</p>
            <p class="subtitle">Keep playing to increase your score.</p>
        </div>
    @elseif($isOver)
        <div class="game-message game-over-message">
            <p>Game over.</p>
            <p class="subtitle">Final Score: {{ number_format($score) }}</p>
        </div>
    @endif

    <!-- Game Board -->
    <div class="board-container">
        <div class="game-board-2048">
            @foreach($board as $index => $value)
                @php
                    $row = intval($index / 4);
                    $col = $index % 4;
                @endphp
                <div class="tile-cell">
                    @if($value > 0)
                        <div class="tile tile-{{ $value }}" 
                             style="background-color: {{ $this->getTileColor($value) }}; 
                                    color: {{ $this->getTileTextColor($value) }};">
                            {{ $value }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Controls -->
    <div class="game-controls-2048">
        <div class="control-row">
            <button wire:click="newGame" 
                    class="control-btn-2048 new-game"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New</span>
            </button>
            
            <button wire:click="undo" 
                    class="control-btn-2048"
                    @disabled(empty($previousState))
                    aria-label="Undo last move">
                <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                <span>Undo</span>
            </button>
        </div>
        
        <div class="mobile-controls">
            <div class="control-grid">
                <div></div>
                <button wire:click="move('up')" class="arrow-btn" title="Swipe or tap Up">↑</button>
                <div></div>
                <button wire:click="move('left')" class="arrow-btn" title="Swipe or tap Left">←</button>
                <div></div>
                <button wire:click="move('right')" class="arrow-btn" title="Swipe or tap Right">→</button>
                <div></div>
                <button wire:click="move('down')" class="arrow-btn" title="Swipe or tap Down">↓</button>
                <div></div>
            </div>
        </div>
    </div>
</div>
