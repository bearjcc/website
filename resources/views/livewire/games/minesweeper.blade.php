<div class="minesweeper-game" x-data="{ showRules: false }">
    
    <!-- Game Header -->
    <div class="game-header">
        <h2>Minesweeper</h2>
        <button @click="showRules = !showRules" class="rules-button">
            <span x-show="!showRules">Show Rules</span>
            <span x-show="showRules">Hide Rules</span>
        </button>
    </div>

    <!-- Rules (toggleable) -->
    <div x-show="showRules" x-transition class="game-rules">
        <p><strong>How to Play:</strong></p>
        <ul>
            <li>Left-click to reveal a square - numbers show adjacent mines</li>
            <li>Right-click to flag/unflag suspected mines</li>
            <li>Clear all safe squares without hitting a mine to win</li>
            <li>Use logic to deduce mine locations from the numbers</li>
        </ul>
        <p><strong>Difficulties:</strong></p>
        <ul>
            <li><strong>Beginner</strong>: 9×9 grid, 10 mines</li>
            <li><strong>Intermediate</strong>: 16×16 grid, 40 mines</li>
            <li><strong>Expert</strong>: 30×16 grid, 99 mines</li>
        </ul>
    </div>

    <!-- Difficulty Selection -->
    @if($showDifficultySelector && !$gameStarted)
        <div class="difficulty-selection">
            <h3>Select Difficulty</h3>
            <div class="difficulty-buttons">
                <button wire:click="selectDifficulty('beginner')" class="difficulty-btn">
                    Beginner<br><small>9×9 (10 mines)</small>
                </button>
                <button wire:click="selectDifficulty('intermediate')" class="difficulty-btn">
                    Intermediate<br><small>16×16 (40 mines)</small>
                </button>
                <button wire:click="selectDifficulty('expert')" class="difficulty-btn">
                    Expert<br><small>30×16 (99 mines)</small>
                </button>
            </div>
        </div>
    @endif

    <!-- Game Status -->
    <div class="game-status">
        <div class="status-grid">
            <div class="status-item">
                <span class="label">Mines:</span>
                <span class="value">{{ $mineCount - $flagsUsed }}</span>
            </div>
            <div class="status-item">
                <span class="label">Flags:</span>
                <span class="value">{{ $flagsUsed }}</span>
            </div>
            <div class="status-item">
                <span class="label">Revealed:</span>
                <span class="value">{{ $squaresRevealed }}</span>
            </div>
        </div>
        
        @if($gameWon)
            <p class="winner-message">You won. All mines found.</p>
        @elseif($gameOver && !$gameWon)
            <p class="game-over-message">Game over. Mine detonated.</p>
        @endif
    </div>

    <!-- Game Board -->
    <div class="board-container">
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
                         @contextmenu.prevent="$wire.flagCell({{ $cell['x'] }}, {{ $cell['y'] }})">
                        
                        @if($cell['flagged'])
                            <span class="flag">🚩</span>
                        @elseif($cell['revealed'])
                            @if($cell['type'] === 'mine')
                                <span class="mine">💣</span>
                            @elseif($cell['number'] > 0)
                                <span class="number number-{{ $cell['number'] }}">{{ $cell['number'] }}</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Game Controls -->
    <div class="game-controls">
        <button wire:click="newGame" 
                class="control-btn new-game"
                aria-label="Start new game">
            <x-heroicon-o-arrow-path class="w-4 h-4" />
            <span>New</span>
        </button>
    </div>

</div>
