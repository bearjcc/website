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

Separate AI logic into dedicated class:

```php
<?php

namespace App\Games\TicTacToe;

class TicTacToeAI
{
    public static function getBestMove(array $state, string $difficulty = 'medium'): array
    {
        return match($difficulty) {
            'easy' => self::getRandomMove($state),
            'medium' => self::getDecentMove($state),
            'hard' => self::getMiniMaxMove($state),
            default => self::getRandomMove($state),
        };
    }

    protected static function getRandomMove(array $state): array
    {
        // Get available cells
        $available = [];
        foreach ($state['board'] as $index => $cell) {
            if ($cell === null) {
                $available[] = $index;
            }
        }
        
        return ['cell' => $available[array_rand($available)]];
    }

    // ... other AI methods
}
```

Use in component:

```php
public function makeAIMove(): void
{
    $move = TicTacToeAI::getBestMove($this->getCurrentState(), $this->aiDifficulty);
    $this->makeMove($move['cell']);
}
```

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

When building a game, ensure:

### Code Quality
- [ ] Engine uses pure static methods
- [ ] State is immutable (return new, don't mutate)
- [ ] No side effects in engine
- [ ] Clear PHPDoc comments
- [ ] Follows PSR-12 (run Pint)

### Reusability
- [ ] Uses `InteractsWithGameState` trait
- [ ] Uses `<x-ui.game-wrapper>` component
- [ ] Standard control buttons with Heroicons
- [ ] Constellation-style completion message
- [ ] HSL color tokens (no hardcoded colors)

### Testability
- [ ] Unit tests for engine methods
- [ ] Feature tests for Livewire component
- [ ] Tests cover edge cases
- [ ] Tests for win conditions
- [ ] Tests for invalid moves

### Accessibility
- [ ] Proper aria-labels
- [ ] Keyboard navigation (where applicable)
- [ ] 44px minimum touch targets
- [ ] Focus states visible
- [ ] `prefers-reduced-motion` respected

### Night Sky Theme
- [ ] Subtle starfield on board (optional)
- [ ] HSL tokens throughout
- [ ] Constellation completion message
- [ ] Calm, minimal UI
- [ ] No emoji

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

