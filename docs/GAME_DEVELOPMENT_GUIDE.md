# Game Development Guide

Framework and patterns for building games in Ursa Minor.

---

## Philosophy

Games are **learning opportunities** to build:
- Clean, reusable patterns
- Excellent framework architecture
- Developer-friendly abstractions
- Foundation for complex future games

**Goal**: Each game improves the framework. Code should be exemplary for learning.

---

## Architecture Overview

### Three-Layer Pattern

```
Livewire Component (UI State)
    ↓
Game Engine (Pure Logic)
    ↓
Game State (Data Structure)
```

**Livewire Component**:
- Manages UI state (selected cells, current player, etc.)
- Handles user interaction
- Coordinates between UI and engine
- Thin layer — delegates to engine

**Game Engine**:
- Pure functions (static methods)
- No side effects, no state
- Returns new state objects
- Easily testable
- Reusable across contexts

**Game State**:
- Plain arrays or value objects
- Serializable for storage
- Passed immutably through engine methods

---

## User flow and routes

**Flow**: Home or Games index → **Game page** (`/{slug}`) → **Game entry** (`/{slug}/play`) → **Play view** (same URL, after [Start game]).

- **Game page** (`route('games.show', $slug)`): Hero (motif, title, tagline), [Play], optional Rules, "More games" links. Only published games; 404 otherwise.
- **Game entry** (`route('games.play', $slug)`): Shown when `$started === false`. Breadcrumb, optional "Who do you want to play?" (for games with opponent choice), rules, [Start game]. Games without opponent choice (e.g. 2048, Sudoku, Minesweeper, Snake) get minimal entry (breadcrumb + rules + Start game).
- **Play view**: Same URL as entry; after [Start game], `$started === true` and the game component is mounted. Use chrome bar (game name + turn/score), play-main wrapper (~28rem), instruction, board, info bubbles, action row (New game, optional Hint), controls hint.

**Routes**: `games.index` = `/games`; `games.show` = `/{game:slug}`; `games.play` = `/{game:slug}/play`. Link cards to `games.show`, not `games.play`. Legacy `/games/tic-tac-toe` etc. redirect 301 to `/{slug}`.

**Adding a new game**: DB entry (slug, title, status, etc.) → add to `$componentMap` in `game-play.blade.php` → create Livewire component and optional engine. If the game has opponent/mode choice, add its slug to `Game::slugsWithOpponentChoice()`.

---

## Creating a New Game

### 1. Database Entry

Add to `games` table via seeder or tinker:

```php
Game::create([
    'slug' => 'chess',
    'title' => 'Chess',
    'type' => 'board',
    'status' => 'published',
    'short_description' => 'Classic strategy.',
]);
```

### 2. Game Engine

Create `app/Games/{GameName}/{GameName}Engine.php`:

```php
<?php

declare(strict_types=1);

namespace App\Games\Chess;

/**
 * Chess engine - pure game logic
 */
class ChessEngine
{
    /**
     * Initialize new game state.
     */
    public static function newGame(array $config = []): array
    {
        return [
            'board' => self::getInitialBoard(),
            'currentPlayer' => 'white',
            'moves' => [],
            'gameOver' => false,
            'winner' => null,
            // ... other state
        ];
    }

    /**
     * Apply a move and return new state.
     * 
     * Engine methods are PURE - no side effects.
     */
    public static function applyMove(array $state, array $move): array
    {
        // Validate move
        if (!self::isValidMove($state, $move)) {
            return $state; // Return unchanged if invalid
        }

        // Create new state (don't mutate)
        $newState = $state;
        $newState['board'] = self::movePiece($state['board'], $move);
        $newState['currentPlayer'] = $state['currentPlayer'] === 'white' ? 'black' : 'white';
        $newState['moves'][] = $move;

        // Check win condition
        if (self::isCheckmate($newState)) {
            $newState['gameOver'] = true;
            $newState['winner'] = $state['currentPlayer'];
        }

        return $newState;
    }

    /**
     * Check if game is over.
     */
    public static function isGameOver(array $state): bool
    {
        return $state['gameOver'] ?? false;
    }

    /**
     * Validate a move.
     */
    protected static function isValidMove(array $state, array $move): bool
    {
        // Implement validation logic
        return true;
    }

    // ... other pure methods
}
```

**Engine Principles**:
- ✅ All methods static (stateless)
- ✅ Return new state (immutable)
- ✅ No side effects (no DB, no session, no logs)
- ✅ Easily testable
- ✅ Documented with PHPDoc

### 3. Livewire Component

Create `app/Livewire/Games/{GameName}.php`:

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Chess\ChessEngine;
use App\Livewire\Concerns\InteractsWithGameState;
use Livewire\Component;

class Chess extends Component
{
    use InteractsWithGameState;

    // State properties
    public array $board = [];
    public string $currentPlayer = 'white';
    public array $moves = [];
    public bool $gameOver = false;
    public ?string $winner = null;
    public ?array $selectedPiece = null;

    /**
     * Initialize game on component mount.
     */
    public function mount(): void
    {
        // Try to load saved state, or start new
        $savedState = $this->loadSavedState();
        
        if ($savedState) {
            $this->restoreFromState($savedState);
        } else {
            $this->newGame();
        }
    }

    /**
     * Start a new game.
     */
    public function newGame(): void
    {
        $state = ChessEngine::newGame();
        $this->syncFromEngine($state);
        $this->resetGame(); // From trait
    }

    /**
     * Handle player move.
     */
    public function makeMove(array $from, array $to): void
    {
        if ($this->gameOver) {
            return;
        }

        $move = ['from' => $from, 'to' => $to];
        $newState = ChessEngine::applyMove($this->getCurrentState(), $move);
        
        $this->syncFromEngine($newState);
        $this->incrementMoveCount();
        $this->saveState();

        if ($newState['gameOver']) {
            $this->completeGame();
        }
    }

    /**
     * Get current state for engine.
     */
    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'currentPlayer' => $this->currentPlayer,
            'moves' => $this->moves,
            'gameOver' => $this->gameOver,
            'winner' => $this->winner,
        ];
    }

    /**
     * Sync component properties from engine state.
     */
    protected function syncFromEngine(array $state): void
    {
        $this->board = $state['board'];
        $this->currentPlayer = $state['currentPlayer'];
        $this->moves = $state['moves'];
        $this->gameOver = $state['gameOver'];
        $this->winner = $state['winner'];
    }

    /**
     * State for localStorage.
     */
    protected function getStateForStorage(): array
    {
        return $this->getCurrentState();
    }

    /**
     * Restore from localStorage.
     */
    protected function restoreFromState(array $state): void
    {
        $this->syncFromEngine($state);
    }

    public function render()
    {
        return view('livewire.games.chess');
    }
}
```

**Component Principles**:
- ✅ Use trait for common behaviors
- ✅ Delegate logic to engine
- ✅ Clear separation: UI state vs game logic
- ✅ localStorage-ready via trait
- ✅ Consistent naming: `getCurrentState()`, `syncFromEngine()`, `newGame()`

### 4. Blade View

Create `resources/views/livewire/games/chess.blade.php`:

```blade
<x-ui.game-wrapper :title="'Chess'" :rules="$rules ?? []">
    {{-- Game Status --}}
    @if($gameOver)
        <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                <p class="text-lg font-semibold text-star">
                    {{ ucfirst($winner) }} wins.
                </p>
                <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
            </div>
            <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
                <span>{{ count($moves) }} moves</span>
            </div>
        </div>
    @else
        <div class="text-center text-sm text-ink/70">
            Current turn: <strong class="text-ink">{{ ucfirst($currentPlayer) }}</strong>
        </div>
    @endif

    {{-- Game Board --}}
    <div class="board-container">
        <div class="chess-board">
            {{-- Implement board rendering --}}
        </div>
    </div>

    {{-- Controls --}}
    <x-slot:controls>
        <div class="control-buttons">
            <button wire:click="newGame" 
                    class="control-btn new-game"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New</span>
            </button>
        </div>
    </x-slot:controls>
</x-ui.game-wrapper>
```

**View Principles**:
- ✅ Use `<x-ui.game-wrapper>` for structure
- ✅ Constellation-style completion messages
- ✅ Consistent status display
- ✅ Standard control buttons
- ✅ Minimal copy

### 5. Add Visual Motif

Update `resources/views/components/ui/game-card.blade.php` with new motif:

```blade
@case('chess')
    {{-- Knight piece or board pattern --}}
    <svg>...</svg>
@break
```

### 6. Route Mapping

Update `resources/views/livewire/pages/game-play.blade.php`:

```php
$componentMap = [
    // ... existing
    'chess' => 'games.chess',
];
```

---

## Common Patterns

### Pattern: newGame()

Every game component should have:

```php
public function newGame(?string $difficulty = null): void
{
    // 1. Get initial state from engine
    $state = GameEngine::newGame(['difficulty' => $difficulty ?? $this->difficulty]);
    
    // 2. Sync to component
    $this->syncFromEngine($state);
    
    // 3. Reset lifecycle
    $this->resetGame(); // From trait
    
    // 4. Start timer if needed
    $this->startTimer(); // From trait
}
```

### Pattern: makeMove()

```php
public function makeMove($moveData): void
{
    // 1. Guard: Don't allow moves if game over
    if ($this->gameOver) {
        return;
    }

    // 2. Get current state
    $current = $this->getCurrentState();
    
    // 3. Delegate to engine
    $newState = GameEngine::applyMove($current, $moveData);
    
    // 4. Sync back
    $this->syncFromEngine($newState);
    
    // 5. Track move
    $this->incrementMoveCount();
    
    // 6. Save state
    $this->saveState();
    
    // 7. Check completion
    if ($newState['gameOver']) {
        $this->completeGame();
    }
}
```

### Pattern: State Sync

```php
protected function getCurrentState(): array
{
    return [
        'board' => $this->board,
        'currentPlayer' => $this->currentPlayer,
        // ... all game state
    ];
}

protected function syncFromEngine(array $state): void
{
    $this->board = $state['board'];
    $this->currentPlayer = $state['currentPlayer'];
    // ... sync all properties
}
```

**Why this pattern?**
- Clear boundary between UI and logic
- Easy to test engine independently
- State can be serialized for storage
- Undo/redo becomes trivial (keep state history)

---

## Game Engines: Best Practices

### Pure Functions Only

```php
// ✅ GOOD - Pure function
public static function applyMove(array $state, array $move): array
{
    $newState = $state;
    $newState['board'] = self::updateBoard($state['board'], $move);
    return $newState;
}

// ❌ BAD - Mutates input
public static function applyMove(array &$state, array $move): void
{
    $state['board'] = self::updateBoard($state['board'], $move);
}

// ❌ BAD - Side effects
public static function applyMove(array $state, array $move): array
{
    Log::info('Move applied'); // Side effect!
    return $newState;
}
```

### Constants for Configuration

```php
class SudokuEngine
{
    public const DIFFICULTIES = [
        'beginner' => ['clues' => 45, 'label' => 'Beginner'],
        'easy' => ['clues' => 38, 'label' => 'Easy'],
        'medium' => ['clues' => 30, 'label' => 'Medium'],
    ];

    public const MAX_HINTS = [
        'beginner' => 6,
        'easy' => 5,
        'medium' => 3,
    ];
}
```

### Comprehensive State

State arrays should be complete and explicit:

```php
// ✅ GOOD - Everything explicit
return [
    'board' => $board,
    'currentPlayer' => 'white',
    'moves' => [],
    'gameOver' => false,
    'winner' => null,
    'lastMove' => null,
    'castlingRights' => ['white' => true, 'black' => true],
];

// ❌ BAD - Implicit or missing fields
return [
    'board' => $board,
    // Where's currentPlayer? Winner? Moves?
];
```

### Testable

Every engine method should be easily testable:

```php
public function test_checkmate_detection(): void
{
    $state = [
        'board' => $this->getCheckmateBoard(),
        // ... other state
    ];

    $result = ChessEngine::isCheckmate($state);

    $this->assertTrue($result);
}
```

---

## UI Patterns

### Completion Messages (Constellation Style)

Standard pattern for all games:

```blade
@if($gameComplete)
    <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
        <div class="flex items-center justify-center gap-2">
            <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
            <p class="text-lg font-semibold text-star">Puzzle complete.</p>
            <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
        </div>
        <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
            <span>{{ $difficulty }}</span>
            <span class="w-1 h-1 rounded-full bg-ink/40"></span>
            <span>{{ $moveCount }} moves</span>
        </div>
    </div>
@endif
```

### Control Buttons (Standard Pattern)

```blade
<div class="game-controls">
    <div class="control-buttons">
        <button wire:click="newGame" 
                class="control-btn new-game"
                aria-label="Start new game">
            <x-heroicon-o-arrow-path class="w-4 h-4" />
            <span>New</span>
        </button>
        
        {{-- Optional: Undo --}}
        <button wire:click="undo" 
                class="control-btn"
                @disabled(!$canUndo)
                aria-label="Undo last move">
            <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
            <span>Undo</span>
        </button>
        
        {{-- Optional: Hint --}}
        <button wire:click="useHint" 
                class="control-btn"
                @disabled($hintsRemaining === 0)
                aria-label="Use hint - {{ $hintsRemaining }} remaining">
            <x-heroicon-o-light-bulb class="w-4 h-4" />
            <span>Hint</span>
        </button>
    </div>
</div>
```

**Standard Classes**:
- `.control-btn` — Base button style (glass, HSL tokens)
- `.control-btn.active` — Active state (star background)
- `.control-btn.new-game` — Primary action (always star background)
- `.control-btn:disabled` — Disabled state (40% opacity)

### Game Boards (Night Sky Theme)

Apply subtle starfield to boards:

```css
.chess-board {
    background: 
        radial-gradient(1px 1px at 20% 30%, hsl(var(--ink) / .08), transparent),
        radial-gradient(1px 1px at 80% 70%, hsl(var(--ink) / .06), transparent),
        hsl(var(--space-900));
}
```

**Use HSL tokens**:
- Board background: `hsl(var(--space-800))`
- Cell borders: `hsl(var(--border) / .3)`
- Active cells: `hsl(var(--star) / .2)`
- Player pieces: `hsl(var(--star))` or `hsl(var(--constellation))`

---

## localStorage Persistence

### Frontend Pattern (Alpine.js)

```javascript
// resources/js/game-storage.js
window.gameStorage = {
    save(key, state) {
        localStorage.setItem(key, JSON.stringify(state));
    },
    
    load(key) {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    },
    
    clear(key) {
        localStorage.removeItem(key);
    }
};

// Listen for Livewire events
document.addEventListener('livewire:init', () => {
    Livewire.on('save-game-state', ({ key, state }) => {
        gameStorage.save(key, state);
    });
    
    Livewire.on('clear-game-state', ({ key }) => {
        gameStorage.clear(key);
    });
});
```

### Livewire Integration

Use the `InteractsWithGameState` trait:

```php
use App\Livewire\Concerns\InteractsWithGameState;

class YourGame extends Component
{
    use InteractsWithGameState;

    // Trait provides:
    // - saveState()
    // - loadSavedState()
    // - clearSavedState()
    // - getStateForStorage()
    // - restoreFromState()
}
```

---

## Testing Games

### Engine Tests (Unit Tests)

Test engines in isolation:

```php
<?php

namespace Tests\Unit\Games;

use App\Games\Chess\ChessEngine;
use PHPUnit\Framework\TestCase;

class ChessEngineTest extends TestCase
{
    public function test_new_game_initializes_correctly(): void
    {
        $state = ChessEngine::newGame();

        $this->assertArrayHasKey('board', $state);
        $this->assertArrayHasKey('currentPlayer', $state);
        $this->assertEquals('white', $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
    }

    public function test_valid_move_changes_state(): void
    {
        $state = ChessEngine::newGame();
        $move = ['from' => [1, 0], 'to' => [2, 0]]; // Pawn forward

        $newState = ChessEngine::applyMove($state, $move);

        $this->assertNotEquals($state, $newState);
        $this->assertEquals('black', $newState['currentPlayer']);
    }

    public function test_invalid_move_returns_unchanged_state(): void
    {
        $state = ChessEngine::newGame();
        $invalidMove = ['from' => [0, 0], 'to' => [7, 7]]; // Invalid

        $newState = ChessEngine::applyMove($state, $invalidMove);

        $this->assertEquals($state, $newState);
    }

    public function test_checkmate_detected(): void
    {
        $state = $this->createCheckmatePosition();

        $isCheckmate = ChessEngine::isCheckmate($state);

        $this->assertTrue($isCheckmate);
    }
}
```

### Component Tests (Feature Tests)

Test Livewire components:

```php
<?php

namespace Tests\Feature\Games;

use App\Livewire\Games\Chess;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChessTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders(): void
    {
        Livewire::test(Chess::class)
            ->assertStatus(200)
            ->assertSee('Chess');
    }

    public function test_new_game_initializes_board(): void
    {
        Livewire::test(Chess::class)
            ->call('newGame')
            ->assertSet('currentPlayer', 'white')
            ->assertSet('gameOver', false)
            ->assertCount('moves', 0);
    }

    public function test_player_can_make_valid_move(): void
    {
        Livewire::test(Chess::class)
            ->call('newGame')
            ->call('makeMove', ['from' => [1, 0], 'to' => [2, 0]])
            ->assertSet('currentPlayer', 'black');
    }

    public function test_game_prevents_invalid_moves(): void
    {
        Livewire::test(Chess::class)
            ->call('newGame')
            ->call('makeMove', ['from' => [0, 0], 'to' => [7, 7]])
            ->assertSet('currentPlayer', 'white'); // Still white (move rejected)
    }
}
```

---

## Reusable Components

### GameWrapper Component ✅

Use for consistent structure:

```blade
<x-ui.game-wrapper 
    title="Game Name"
    :rules="['Rule 1', 'Rule 2']">
    
    {{-- Game content --}}
    
    <x-slot:controls>
        {{-- Control buttons --}}
    </x-slot:controls>
</x-ui.game-wrapper>
```

### Completion Message Component

For consistency, could create:

```blade
{{-- resources/views/components/ui/game-complete.blade.php --}}
@props(['message' => 'Complete.', 'stats' => []])

<div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
    <div class="flex items-center justify-center gap-2">
        <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
        <p class="text-lg font-semibold text-star">{{ $message }}</p>
        <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
    </div>
    @if($stats)
        <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
            @foreach($stats as $index => $stat)
                @if($index > 0)
                    <span class="w-1 h-1 rounded-full bg-ink/40"></span>
                @endif
                <span>{{ $stat }}</span>
            @endforeach
        </div>
    @endif
</div>
```

Usage:

```blade
<x-ui.game-complete 
    message="Puzzle complete."
    :stats="[ucfirst($difficulty), $hintsUsed . ' hints', $mistakes . ' mistakes']" 
/>
```

---

## Advanced Patterns

### Undo/Redo

Keep state history:

```php
public array $stateHistory = [];
public int $historyIndex = 0;

public function makeMove($move): void
{
    // Before applying move
    $this->stateHistory[] = $this->getCurrentState();
    $this->historyIndex = count($this->stateHistory) - 1;
    
    // Apply move...
}

public function undo(): void
{
    if ($this->historyIndex > 0) {
        $this->historyIndex--;
        $this->restoreFromState($this->stateHistory[$this->historyIndex]);
    }
}

public function redo(): void
{
    if ($this->historyIndex < count($this->stateHistory) - 1) {
        $this->historyIndex++;
        $this->restoreFromState($this->stateHistory[$this->historyIndex]);
    }
}
```

### AI Opponents

#### AI Pattern Overview

AI opponents should:
1. **Live in game engines** as pure static methods
2. **Use `ProvidesAIOpponent` trait** in Livewire components
3. **Offer 3 standard difficulty levels**: easy, medium, impossible

#### AI Difficulty Levels

**Easy AI** — Beatable, makes obvious mistakes
- Always takes winning moves (100%)
- Occasionally blocks opponent (30-50%)
- Otherwise plays randomly
- Good for casual players and children

**Medium AI** — Challenging but fair
- Always takes winning moves (100%)
- Usually blocks opponent (80-90%)
- Takes strategic positions (center, corners)
- Makes occasional suboptimal moves

**Impossible AI** — Perfect play
- Uses minimax with alpha-beta pruning
- Never loses (can only win or draw)
- Provides ultimate challenge
- Educational for studying optimal play

#### Engine AI Methods Pattern

**Add these methods to your game engine:**

```php
<?php

namespace App\Games\YourGame;

class YourGameEngine
{
    /**
     * Easy AI: Always wins, sometimes blocks, mostly random.
     */
    public static function aiEasy(array $state, string $player): int
    {
        $availableMoves = self::getAvailableMoves($state);
        
        // 1. Always try to win (100%)
        foreach ($availableMoves as $move) {
            $testState = self::applyMove($state, ['move' => $move, 'player' => $player]);
            if (self::isWinning($testState, $player)) {
                return $move;
            }
        }
        
        // 2. Sometimes block opponent (30-50%)
        if (mt_rand(1, 100) <= 40) {
            $opponent = self::getOpponent($player);
            foreach ($availableMoves as $move) {
                $testState = self::applyMove($state, ['move' => $move, 'player' => $opponent]);
                if (self::isWinning($testState, $opponent)) {
                    return $move;
                }
            }
        }
        
        // 3. Otherwise random
        return $availableMoves[array_rand($availableMoves)];
    }

    /**
     * Medium AI: Strategic but fallible.
     */
    public static function aiMedium(array $state, string $player): int
    {
        $opponent = self::getOpponent($player);
        $availableMoves = self::getAvailableMoves($state);
        
        // 1. Always try to win
        foreach ($availableMoves as $move) {
            $testState = self::applyMove($state, ['move' => $move, 'player' => $player]);
            if (self::isWinning($testState, $player)) {
                return $move;
            }
        }
        
        // 2. Usually block opponent (80-90%)
        if (mt_rand(1, 100) <= 85) {
            foreach ($availableMoves as $move) {
                $testState = self::applyMove($state, ['move' => $move, 'player' => $opponent]);
                if (self::isWinning($testState, $opponent)) {
                    return $move;
                }
            }
        }
        
        // 3. Take strategic positions
        $strategicMoves = self::getStrategicMoves($state, $availableMoves);
        if (!empty($strategicMoves)) {
            return $strategicMoves[array_rand($strategicMoves)];
        }
        
        // 4. Fallback to random
        return $availableMoves[array_rand($availableMoves)];
    }

    /**
     * Impossible AI: Perfect minimax play.
     * 
     * This is a reference implementation for Tic-Tac-Toe.
     * Adapt the logic for your specific game.
     */
    public static function aiImpossible(array $state, string $player): int
    {
        // Implement minimax with alpha-beta pruning
        // See TicTacToe\Engine::bestMoveMinimax() for full example
        
        $bestMove = -1;
        $bestValue = -1000;
        
        foreach (self::getAvailableMoves($state) as $move) {
            $newState = self::applyMove($state, ['move' => $move, 'player' => $player]);
            $moveValue = self::minimax($newState, self::getOpponent($player), 0, -1000, 1000, $player);
            
            if ($moveValue > $bestValue) {
                $bestValue = $moveValue;
                $bestMove = $move;
            }
        }
        
        return $bestMove;
    }

    /**
     * Minimax algorithm with alpha-beta pruning.
     */
    private static function minimax(array $state, string $currentPlayer, int $depth, int $alpha, int $beta, string $maximizingPlayer): int
    {
        // Check terminal states
        if (self::isGameOver($state)) {
            return self::evaluateState($state, $maximizingPlayer, $depth);
        }
        
        $availableMoves = self::getAvailableMoves($state);
        
        if ($currentPlayer === $maximizingPlayer) {
            // Maximizing player
            $maxEval = -1000;
            foreach ($availableMoves as $move) {
                $newState = self::applyMove($state, ['move' => $move, 'player' => $currentPlayer]);
                $eval = self::minimax($newState, self::getOpponent($currentPlayer), $depth + 1, $alpha, $beta, $maximizingPlayer);
                $maxEval = max($maxEval, $eval);
                $alpha = max($alpha, $eval);
                if ($beta <= $alpha) break; // Alpha-beta pruning
            }
            return $maxEval;
        } else {
            // Minimizing player
            $minEval = 1000;
            foreach ($availableMoves as $move) {
                $newState = self::applyMove($state, ['move' => $move, 'player' => $currentPlayer]);
                $eval = self::minimax($newState, self::getOpponent($currentPlayer), $depth + 1, $alpha, $beta, $maximizingPlayer);
                $minEval = min($minEval, $eval);
                $beta = min($beta, $eval);
                if ($beta <= $alpha) break; // Alpha-beta pruning
            }
            return $minEval;
        }
    }

    /**
     * Evaluate terminal state for minimax.
     */
    private static function evaluateState(array $state, string $player, int $depth): int
    {
        $winner = self::getWinner($state);
        
        if ($winner === $player) {
            return 10 - $depth; // Prefer faster wins
        }
        
        if ($winner !== null) {
            return $depth - 10; // Prefer slower losses
        }
        
        return 0; // Draw
    }
}
```

#### Component Integration with Trait

**Use the `ProvidesAIOpponent` trait:**

```php
<?php

namespace App\Livewire\Games;

use App\Games\YourGame\YourGameEngine;
use App\Livewire\Concerns\ProvidesAIOpponent;
use App\Livewire\Concerns\InteractsWithGameState;
use Livewire\Component;

class YourGame extends Component
{
    use InteractsWithGameState;
    use ProvidesAIOpponent;

    public array $board = [];
    public string $currentPlayer = 'X';
    public bool $gameOver = false;
    public ?string $winner = null;

    public function mount(): void
    {
        $this->playerSymbol = 'X'; // Default for AI mode
        $this->newGame();
    }

    public function newGame(): void
    {
        $state = YourGameEngine::newGame();
        $this->syncFromEngine($state);
        $this->resetGame();
    }

    public function makeMove($position): void
    {
        if ($this->gameOver) return;
        
        // Prevent moves during AI turn
        if ($this->isAIMode() && $this->currentPlayer !== $this->playerSymbol) {
            return;
        }

        // Apply player move
        $state = YourGameEngine::applyMove(
            $this->getCurrentState(),
            ['move' => $position, 'player' => $this->currentPlayer]
        );
        
        $this->syncFromEngine($state);
        $this->incrementMoveCount();

        // Check if game over
        if ($state['gameOver']) {
            $this->completeGame();
            return;
        }

        // Switch player
        $this->currentPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';

        // Trigger AI move if applicable
        if ($this->isAITurn()) {
            $this->makeAiMove();
        }
    }

    protected function makeAiMove(): void
    {
        $engine = new YourGameEngine();
        
        // Select AI difficulty
        $aiMove = match ($this->getAIDifficulty()) {
            'easy' => $engine::aiEasy($this->board, $this->currentPlayer),
            'medium' => $engine::aiMedium($this->board, $this->currentPlayer),
            'impossible' => $engine::aiImpossible($this->board, $this->currentPlayer),
            default => $engine::aiImpossible($this->board, $this->currentPlayer),
        };

        // Apply AI move
        $state = $engine::applyMove(
            $this->getCurrentState(),
            ['move' => $aiMove, 'player' => $this->currentPlayer]
        );
        
        $this->syncFromEngine($state);
        $this->incrementMoveCount();

        // Check if game over
        if ($state['gameOver']) {
            $this->completeGame();
            return;
        }

        // Switch back to player
        $this->currentPlayer = $this->playerSymbol;
    }

    protected function getCurrentPlayer(): string
    {
        return $this->currentPlayer;
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'currentPlayer' => $this->currentPlayer,
            'gameOver' => $this->gameOver,
            'winner' => $this->winner,
        ];
    }

    protected function syncFromEngine(array $state): void
    {
        $this->board = $state['board'];
        $this->currentPlayer = $state['currentPlayer'];
        $this->gameOver = $state['gameOver'];
        $this->winner = $state['winner'];
    }

    public function render()
    {
        return view('livewire.games.your-game');
    }
}
```

#### UI Pattern for AI Selection

**Add mode selection to game view:**

```blade
{{-- Game Mode Selection (show before game starts) --}}
@if($moveCount === 0)
    <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 space-y-4">
        <h3 class="text-lg font-semibold text-ink">Mode</h3>
        
        <div class="flex flex-wrap gap-2">
            <button wire:click="setGameMode('pvp')" 
                    class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'pvp' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                Pass & Play
            </button>
            
            <button wire:click="setGameMode('ai-easy', 'X')" 
                    class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-easy' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                Easy
            </button>
            
            <button wire:click="setGameMode('ai-medium', 'X')" 
                    class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-medium' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                Medium
            </button>
            
            <button wire:click="setGameMode('ai-impossible', 'X')" 
                    class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-impossible' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                Impossible
            </button>
        </div>
    </div>
@endif

{{-- During gameplay, show current mode --}}
@if($gameMode !== 'pvp')
    <p class="text-sm text-ink/60 text-center">
        You: {{ $playerSymbol }} vs AI ({{ ucfirst(str_replace('ai-', '', $gameMode)) }})
    </p>
@endif
```

#### Testing AI Opponents

**Test all difficulty levels:**

```php
<?php

namespace Tests\Feature\Games;

use App\Livewire\Games\YourGame;
use Livewire\Livewire;
use Tests\TestCase;

class YourGameAITest extends TestCase
{
    public function test_easy_ai_makes_move(): void
    {
        Livewire::test(YourGame::class)
            ->call('setGameMode', 'ai-easy', 'X')
            ->call('makeMove', 0)
            ->assertSet('moveCount', 2); // Player + AI
    }

    public function test_medium_ai_makes_move(): void
    {
        Livewire::test(YourGame::class)
            ->call('setGameMode', 'ai-medium', 'X')
            ->call('makeMove', 0)
            ->assertSet('moveCount', 2);
    }

    public function test_impossible_ai_makes_move(): void
    {
        Livewire::test(YourGame::class)
            ->call('setGameMode', 'ai-impossible', 'X')
            ->call('makeMove', 0)
            ->assertSet('moveCount', 2);
    }

    public function test_player_cannot_move_during_ai_turn(): void
    {
        Livewire::test(YourGame::class)
            ->call('setGameMode', 'ai-easy', 'O') // AI goes first
            ->call('makeMove', 0) // Try to move (should be ignored)
            ->assertSet('moveCount', 1); // Only AI moved
    }
}
```

#### Real-World Example: Tic-Tac-Toe

**See working implementation:**
- Engine: `app/Games/TicTacToe/Engine.php`
- Component: `app/Livewire/Games/TicTacToe.php`
- View: `resources/views/livewire/games/tic-tac-toe.blade.php`

**Key files from proven games repo** (`C:\Users\bearj\Herd\games`):
- `app/Games/TicTacToe/Engine.php` — Complete AI implementation
- `app/Games/Connect4/` — AI for gravity-based game
- `app/Games/Checkers/` — AI for capture-based strategy
- `app/Games/Chess/` — Advanced AI with piece evaluation

---

## File Organization

```
app/
├── Games/
│   ├── Chess/
│   │   ├── ChessEngine.php      # Pure game logic
│   │   ├── ChessAI.php          # AI opponent (if needed)
│   │   └── ChessState.php       # Value object (optional)
│   ├── Contracts/
│   │   └── GameInterface.php    # Shared interface
│   └── ... other games
├── Livewire/
│   ├── Concerns/
│   │   └── InteractsWithGameState.php  # Shared behaviors
│   └── Games/
│       ├── Chess.php            # Livewire component
│       └── ... other components
```

---

## Developer Experience Checklist

When building a game, use the verification guide: **.cursor/rules/verification-guide.mdc**.

---

## Examples

### Simple Game (Tic-Tac-Toe)

**Complexity**: Low  
**Pattern**: Board array, turn-based, win detection  
**Reference**: `app/Livewire/Games/TicTacToe.php`

### Medium Complexity (Sudoku)

**Complexity**: Medium  
**Pattern**: Constraint validation, hints, notes mode  
**Reference**: `app/Livewire/Games/Sudoku.php`

### Animation-Heavy (Snake)

**Complexity**: Medium  
**Pattern**: Real-time game loop, collision detection, growth  
**Reference**: `app/Livewire/Games/Snake.php`

### Physics (Connect 4)

**Complexity**: Medium  
**Pattern**: Gravity simulation, win pattern detection  
**Reference**: `app/Livewire/Games/Connect4.php`

---

## Future Enhancements

### Multiplayer Pattern

For real-time multiplayer:

```php
use Livewire\Attributes\On;

class Chess extends Component
{
    public string $gameId;
    public string $playerId;

    #[On('opponent-moved')]
    public function handleOpponentMove(array $move): void
    {
        $this->makeMove($move);
        $this->dispatch('board-updated');
    }

    public function makeMove($move): void
    {
        // Apply move locally
        // ...
        
        // Broadcast to opponent
        $this->dispatch('player-moved', $move)->to('chess.'.$this->gameId);
    }
}
```

### Score Tracking

Add to engines:

```php
public static function calculateScore(array $state): int
{
    $baseScore = 1000;
    $movePenalty = count($state['moves']) * 5;
    $timePenalty = ($state['timeElapsed'] ?? 0) * 2;
    $hintsUsed = ($state['hintsUsed'] ?? 0) * 50;
    
    return max(0, $baseScore - $movePenalty - $timePenalty - $hintsUsed);
}
```

---

## Questions?

When building a new game:
1. Start with the engine (pure logic)
2. Write engine tests first (TDD)
3. Create Livewire component using trait
4. Use GameWrapper for consistent UI
5. Add visual motif to game-card component
6. Test thoroughly
7. Document any new patterns

**Remember**: Each game should improve the framework for the next one.

