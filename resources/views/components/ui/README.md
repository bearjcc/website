# UI Components

Reusable Blade components for Ursa Minor Games.

---

## Layout Components

### `<x-layouts.app>`

Main application layout with nav, footer, starfield.

```blade
<x-layouts.app>
    <x-slot:title>Page Title</x-slot:title>
    
    {{-- Content --}}
</x-layouts.app>
```

---

## Game Components

### `<x-ui.game-wrapper>`

Standard game page structure (header, back nav, rules, controls).

**Props**:
- `title` — Game name (string, required)
- `rules` — Array of rule strings or HTML (optional)
- `showControls` — Whether to render controls slot (bool, default true)

**Usage**:
```blade
<x-ui.game-wrapper 
    title="Chess"
    :rules="['Get three in a row', 'No diagonal moves']">
    
    {{-- Game status, board, etc. --}}
    
    <x-slot:controls>
        <button wire:click="newGame" class="control-btn new-game">
            <x-heroicon-o-arrow-path class="w-4 h-4" />
            <span>New</span>
        </button>
    </x-slot:controls>
</x-ui.game-wrapper>
```

### `<x-ui.game-card>`

Visual-first game card for homepage/lobby.

**Props**:
- `href` — Link to game (string, required)
- `title` — Game name (string, required)
- `motif` — Visual motif type (string: 'tictactoe', 'chess', 'sudoku', etc.)
- `aria` — Custom aria-label (string, optional)

**Usage**:
```blade
<x-ui.game-card
    :href="route('games.play', 'chess')"
    title="Chess"
    motif="chess"
/>
```

**Available Motifs**:
- `tictactoe` — 3×3 grid
- `chess` — Knight piece
- `checkers` — Checkerboard pattern
- `connect4` — Connect 4 grid with circles
- `sudoku` / `puzzle` — Sudoku grid
- `minesweeper` — Grid with flag
- `snake` — Snake path
- `2048` — 4×4 tile grid
- `cards` / `solitaire` — Playing cards stack
- `memory` — Memory card pairs
- `board` — Generic board (fallback)
- `sparkles` — Generic fallback

---

## UI Elements

### `<x-ui.logo-lockup>`

Ursa Minor logo (bear + constellation).

**Props**:
- `class` — Additional CSS classes (string, optional)

**Usage**:
```blade
<x-ui.logo-lockup class="w-[280px] md:w-[360px]" />
```

### `<x-ui.nav-logo>`

Compact logo for navigation.

**Usage**:
```blade
<x-ui.nav-logo />
```

### `<x-ui.horizon-footer>`

Footer with sunset line, earth silhouette, back-to-top.

**Usage**:
```blade
<x-ui.horizon-footer />
```

---

## Content Components

### `<x-ui.section-header>`

Section header with kicker, title, subtitle.

**Props**:
- `kicker` — Small label above title (string, optional)
- `title` — Main heading (string, optional)
- `subtitle` — Body text below title (string, optional)

**Usage**:
```blade
<x-ui.section-header
    kicker="Available now"
    title="Play in your browser"
    subtitle="No sign-up required."
/>
```

### `<x-ui.card>`

General purpose content card.

**Props**:
- `title` — Card title (string, required)
- `subtitle` — Description (string, optional)
- `href` — Link URL (string, optional)
- `icon` — Heroicon name (string, optional)
- `meta` — Right-aligned metadata (string, optional)

**Usage**:
```blade
<x-ui.card
    title="Latest Update"
    subtitle="New features added"
    href="/blog/update"
    icon="newspaper"
    meta="2 days ago"
/>
```

### `<x-ui.cta-row>`

CTA buttons (primary + secondary).

**Props**:
- `primaryHref` — Primary button URL (string, required)
- `primaryLabel` — Primary button text (string, required)
- `secondaryHref` — Secondary button URL (string, optional)
- `secondaryLabel` — Secondary button text (string, optional)

**Usage**:
```blade
<x-ui.cta-row
    :primaryHref="route('games.index')"
    primaryLabel="Play"
    :secondaryHref="route('about')"
    secondaryLabel="Learn"
/>
```

---

## Flux UI Components

### `<x-ui.flux-button>`

Flux UI button wrapper.

**Variants**: `primary`, `secondary`

**Usage**:
```blade
<x-ui.flux-button variant="primary">
    Click me
</x-ui.flux-button>
```

### `<x-ui.flux-card>`

Flux UI card wrapper.

### `<x-ui.flux-input>`

Flux UI input wrapper.

---

## Carousel

### `<x-ui.carousel>`

Embla carousel with constellation pagination.

**Usage**:
```blade
<x-ui.carousel>
    @foreach($items as $item)
        <div>{{ $item->name }}</div>
    @endforeach
</x-ui.carousel>
```

**Features**:
- Glass navigation arrows
- Constellation-style dots with connecting lines
- Active dot highlighted with star glow
- Keyboard accessible
- Respects `prefers-reduced-motion`

---

## CSS Utility Classes

### Layout
- `.section` — Max-width 960px container, centered
- `.glass` — Glass morphism effect

### Typography
- `.h1`, `.h2`, `.h3`, `.h4`, `.h5`, `.h6` — Heading scales
- `.p`, `.body` — Body text
- `.kicker` — Small uppercase label

### Buttons
- `.btn-primary` — Star-yellow primary action
- `.btn-secondary` — Glass outline button
- `.control-btn` — Game control button
- `.control-btn.new-game` — Primary game button
- `.control-btn.active` — Active state

### Game UI
- `.board-container` — Centers game board
- `.sudoku-board` — Sudoku 9×9 grid
- `.connect4-board` / `.game-board` — Connect 4 7×6 grid
- `.minesweeper-board` — Minesweeper grid
- `.snake-board` — Snake 20×15 grid
- `.game-board-2048` — 2048 4×4 grid
- `.number-buttons` — Number input grid
- `.control-buttons` — Control button row

---

## Common Game UI Patterns

### Constellation Completion Message

```blade
@if($gameComplete)
    <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
        <div class="flex items-center justify-center gap-2">
            <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
            <p class="text-lg font-semibold text-star">Puzzle complete.</p>
            <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
        </div>
        <div class="flex items-center justify-center gap-3 text-sm text-ink/70">
            <span>{{ ucfirst($difficulty) }}</span>
            <span class="w-1 h-1 rounded-full bg-ink/40"></span>
            <span>{{ $moveCount }} moves</span>
        </div>
    </div>
@endif
```

### Standard Control Buttons

```blade
<div class="game-controls">
    <div class="control-buttons">
        <button wire:click="newGame" 
                class="control-btn new-game"
                aria-label="Start new game">
            <x-heroicon-o-arrow-path class="w-4 h-4" />
            <span>New</span>
        </button>
    </div>
</div>
```

### Status Display

```blade
<div class="flex justify-center gap-8 text-sm text-ink/70">
    <div><span class="text-ink/50">Label:</span> <strong class="text-ink">Value</strong></div>
</div>
```

---

## Developer Guidelines

### Creating New Components

1. **Location**: `resources/views/components/ui/`
2. **Naming**: `kebab-case.blade.php`
3. **Props**: Use `@props()` directive
4. **Defaults**: Provide sensible defaults
5. **Documentation**: Add to this README

### Using Components

```blade
{{-- Self-closing if no slot --}}
<x-ui.logo-lockup />

{{-- With content --}}
<x-ui.card title="Title">
    Content here
</x-ui.card>

{{-- With named slots --}}
<x-ui.game-wrapper title="Game">
    Content
    
    <x-slot:controls>
        Controls here
    </x-slot:controls>
</x-ui.game-wrapper>
```

### Component Standards

**DO**:
- Use HSL color tokens
- Provide proper aria-labels
- Meet 44px touch targets
- Respect `prefers-reduced-motion`
- Keep minimal and focused

**DON'T**:
- Use inline styles
- Use emoji
- Use hardcoded colors
- Duplicate markup across components

---

## Questions?

**Where is X component?** Check this file  
**How to use Y?** See usage examples above  
**Need new component?** Follow create guidelines and add to this README
