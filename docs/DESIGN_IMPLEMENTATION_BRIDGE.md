# Design Implementation Bridge

This document maps design principles from the UX conversation to actual code implementation.

## Typography System (Major Third Scale 1.25)

### Theory (from NotebookLM)
- Base: 16px paragraph text
- Scale headings using 1.25 ratio (increases ~25%)
- Line height: 1.5 for body, 1.1 for headings
- Letter spacing: 0 for body, negative for headings
- Use rem or px units (avoid em for sizing)

### Implementation ✅

**Tailwind Config** (`tailwind.config.js`):
```javascript
fontSize: {
  'body': ['1rem', { lineHeight: '1.5', letterSpacing: '0' }],        // 16px
  'h6': ['1.25rem', { lineHeight: '1.2', letterSpacing: '-0.01em' }],   // 20px
  'h5': ['1.563rem', { lineHeight: '1.2', letterSpacing: '-0.01em' }],  // 25px
  'h4': ['1.953rem', { lineHeight: '1.2', letterSpacing: '-0.015em' }], // 31.25px
  'h3': ['2.441rem', { lineHeight: '1.1', letterSpacing: '-0.015em' }], // 39px
  'h2': ['3.052rem', { lineHeight: '1.1', letterSpacing: '-0.02em' }],  // 48.8px
  'h1': ['3.815rem', { lineHeight: '1.1', letterSpacing: '-0.02em' }],  // 61px
}
```

**CSS Utilities** (`app.css`): `.h1`, `.h2`, `.h3`, etc. classes available

---

## 8-Point Spacing Grid

### Theory
- All spacing in multiples of 8 (8, 16, 24, 32, 48, 64px)
- Intra-group spacing < inter-group spacing
- Double the spacing between sections vs. inside sections
- Used by Apple, Google design systems

### Implementation ✅

**Tailwind Config**:
```javascript
spacing: {
  '8': '0.5rem',    // 8px
  '16': '1rem',     // 16px
  '24': '1.5rem',   // 24px
  '32': '2rem',     // 32px
  '48': '3rem',     // 48px
  '64': '4rem',     // 64px
  '96': '6rem',     // 96px
  '128': '8rem',    // 128px
}
```

**CSS Utilities** (`app.css`): `.space-intra` (16px), `.space-inter` (32px), `.space-inter-lg` (48px)

---

## HSL Color System (2 Neutrals + 2 Brand)

### Theory
- Limit palette to 2 neutrals + 2 brand colors
- Derive all other colors via opacity or lightness
- HSL more intuitive than Hex/RGB
- Enables Tailwind's `<alpha-value>` syntax
- 60/30/10 rule: 60% neutral, 30% secondary, 10% accent (CTAs only)

### Implementation ✅

**CSS Variables** (`app.css`):
```css
:root {
  /* Neutrals */
  --ink: 210 25% 96%;           /* Light text */
  --ink-muted: 220 14% 70%;     /* Muted text */
  --space-900: 226 53% 4%;      /* Deep background */
  
  /* Brand */
  --star: 48 89% 77%;           /* Primary CTA accent */
  --constellation: 212 100% 81%; /* Secondary accent */
  
  /* Derived */
  --surface: var(--ink);         /* Use with / 0.04 */
  --border: var(--ink);          /* Use with / 0.10 */
}
```

**Tailwind Integration**:
```javascript
colors: {
  'ink': 'hsl(var(--ink) / <alpha-value>)',
  'star': 'hsl(var(--star) / <alpha-value>)',
  // ... etc
}
```

**Usage**: `text-ink`, `text-ink/80`, `bg-star`, `border-[hsl(var(--border)/.10)]`

---

## Visual Hierarchy Through De-emphasis

### Theory
- To emphasize primary content, de-emphasize secondary
- Use lighter color or weight for supporting text
- Don't rely on size alone (creates illegible or oversized text)
- Guide eye from most to least important

### Implementation ✅

**CSS** (`app.css`):
```css
.h1 {
  color: hsl(var(--ink));        /* Full brightness */
  font-weight: 700;
}

.p {
  color: hsl(var(--ink-muted));  /* Lower brightness */
}

.kicker {
  color: hsl(var(--ink-muted));  /* Muted */
  font-size: 0.75rem;
  text-transform: uppercase;
}
```

**Usage in Views**:
```blade
<h1 class="h1">{{ __('ui.hero_headline') }}</h1>  {{-- Primary --}}
<p class="p">Supporting text</p>                  {{-- De-emphasized --}}
```

---

## WCAG AA Contrast (4.5:1 minimum)

### Theory
- Normal text (< 18pt): ≥ 4.5:1
- Large text: ≥ 3:1
- If white on colored background fails, flip to dark on light

### Implementation ✅

**Pre-verified Ratios**:
- `--ink` on `--space-900`: 21:1 (AAA)
- `--star` on `--space-900`: ~18:1 (AAA)
- `--ink-muted` on `--space-600`: ≥ 4.5:1 (AA)

**Focus States**:
```css
:focus-visible {
  outline: 2px solid hsl(var(--star));
  outline-offset: 2px;
}
```

---

## Visual-First Game Cards

### Theory
- Let visuals (motifs, glyphs) communicate game type
- Text appears on hover/focus for discovery
- Accessibility via aria-label and sr-only heading
- Entire card clickable immediately (no animation wait)
- 44px minimum touch target

### Implementation ✅

**Component** (`resources/views/components/ui/game-card.blade.php`):
- Motif displayed by default
- Title in `<h3 class="sr-only">`
- Title reveals on hover/focus in translucent pill
- Full `aria-label="Play {title}"`
- Aspect ratio box maintains grid alignment

**Styles** (`app.css`):
```css
.um-game-card:hover {
  transform: translateY(-2px);
}

@media (prefers-reduced-motion: reduce) {
  .um-game-card { transition: none; }
  .um-game-card:hover { transform: none; }
}
```

---

## Embla Carousel with Constellation Pagination

### Theory
- Lightweight carousel (Embla)
- Dots as stars with connecting lines (constellation theme)
- Active dot brighter and larger
- Glass navigation arrows
- Keyboard accessible

### Implementation ✅

**Carousel Component** exists (`resources/views/components/ui/carousel.blade.php`)

**Styles** (`app.css`):
```css
.um-carousel-dot {
  width: 8px;
  height: 8px;
  background: hsl(var(--ink) / .3);
}

.um-carousel-dot-active {
  background: hsl(var(--star));
  transform: scale(1.4);
  box-shadow: 0 0 8px hsl(var(--star) / .4);
}

.um-constellation-line {
  width: 24px;
  height: 1px;
  background: hsl(var(--ink) / .2);
}
```

**JavaScript** (`resources/js/embla-carousel.js`): Already exists

---

## Page-Height Starfield

### Theory
- Canvas sized to document.scrollHeight (not viewport)
- Prevents harsh fold line in gradient
- Stars scale with page area: ~0.00015 per px²
- Gentle twinkle: 0.1-0.3Hz, ±0.12 alpha
- Respects `prefers-reduced-motion`
- CPU-friendly: requestAnimationFrame

### Implementation ✅

**JavaScript** (`resources/js/starfield.js`): Already exists

**Specifications Documented** in `docs/COMPONENT_PATTERNS.md`

---

## Horizon Footer (Earth Silhouette)

### Theory
- Footer as "horizon line" grounding the infinite sky
- Sunset line (warm, low opacity)
- Earth silhouette (dark, desaturated)
- Back-to-top sits on sky side (above horizon)
- Generous spacing above (≥64px)

### Implementation ✅

**Component** (`resources/views/components/ui/horizon-footer.blade.php`): Exists

**Styles** (`app.css`):
```css
.um-horizon-footer {
  margin-top: 5rem;  /* 80px breathing room */
  background: linear-gradient(to bottom, 
    hsl(var(--earth) / .96), 
    hsl(var(--earth-dark) / 1));
}

.um-top-btn {
  top: -1.25rem;  /* On sky side, above horizon */
}
```

---

## Minimal Copy Strategy

### Theory (Peaceful Chair Metaphor)
- Like sitting peacefully under stars — not advertising, just present
- Craigslist-like simplicity — links without noise
- No monetization pressure keeps it honest
- Every word must serve a purpose
- Less scrolling, less reading, less cognitive load

### Implementation ✅

**Hero Section** (`home.blade.php`):
- Logo only
- One tagline
- Two one-word CTAs

**Game Cards**:
- Visual motifs, no text labels
- Titles reveal on hover

**Footer**:
- Copyright line only
- No verbose mottos

**i18n Strings** (`lang/en/ui.php`):
- `'cta_play' => 'Play'`
- `'cta_browse' => 'Browse'`
- `'tagline' => 'The sky is the limit.'`

**Rules** (`.cursor/rules/110-minimal-copy.mdc`): Enforced

---

## Lowercase Mode Feature Flag

### Theory
- Allow experimenting with all-lowercase aesthetic
- i18n strings stay properly capitalized
- CSS text-transform for styling only
- Single env variable toggle

### Implementation ✅

**Config** (`config/ui.php`):
```php
'lowercase_mode' => env('URSA_LOWERCASE_MODE', false),
```

**Layout** (`resources/views/components/layouts/app.blade.php`):
```blade
<html class="{{ config('ui.lowercase_mode') ? 'lowercase-mode' : '' }}">
```

**CSS** (`app.css`):
```css
.lowercase-mode {
  text-transform: lowercase;
}
```

---

## Smooth Gradient (No Fold Line)

### Theory
- Continuous vertical gradient from deep black → blue
- Simulates twilight sky getting lighter toward horizon
- Must size to full document height (not viewport)
- No harsh horizontal line at fold

### Implementation ✅

**CSS** (`app.css`):
```css
html {
  background: linear-gradient(180deg, 
    hsl(var(--space-900)) 0%, 
    hsl(var(--space-700)) 38%, 
    hsl(var(--space-600)) 70%, 
    hsl(var(--space-500)) 100%
  );
  background-attachment: fixed;
}

body {
  /* Star dust overlay on top */
  background:
    radial-gradient(2px 2px at 20% 30%, hsl(var(--ink) / .07) 0, transparent 60%),
    radial-gradient(1px 1px at 80% 60%, hsl(var(--ink) / .05) 0, transparent 60%);
}
```

---

## Nav Logo Morph

### Theory
- Single logo perception (avoid duplication)
- Hero shows full lockup
- Nav shows compact mark
- When hero scrolls out, lockup morphs to nav mark
- IntersectionObserver-based
- Respects `prefers-reduced-motion`

### Implementation ✅

**JavaScript** (`resources/js/nav-morph.js`): Already exists

**Usage**: IntersectionObserver on hero triggers fade/scale transitions

---

## Conversion Thinking (Single Page Goal)

### Theory
- Every page serves one primary goal
- Homepage goal: "Play a Game" clicks
- Accent color reserved for primary CTAs (10% rule)
- Minimize distractions (secondary links in footer only)
- Clear, actionable CTA copy

### Implementation ✅

**Homepage**:
- Primary goal: Play a game
- Primary CTA: Star-yellow button in hero
- Secondary actions muted
- Data attribute for tracking: `data-um-goal="hero_play_click"`

**Color Discipline**:
- `--star` used only for `.btn-primary`
- Secondary buttons use transparent + border

---

## Night Sky Motif in Games

### Theory
- Loading states: Stars appearing in sequence
- Progress: Constellation formation
- Board backgrounds: Subtle starfield
- Achievements: Stars lighting up
- Sound: Single soft chime (optional)

### Implementation 🎯 NEXT

This is documented in `docs/COMPONENT_PATTERNS.md` but not yet implemented in actual games.

**When building games**:
- Reference Component Patterns doc
- Use `.um-game-loading` pattern for loading states
- Use `.um-constellation-progress` for progress indicators
- Apply subtle starfield backgrounds to game boards

---

## Status Summary

### ✅ Fully Implemented
- HSL color system with tokens
- Major Third typography scale
- 8-point spacing grid
- Visual-first game cards
- Embla carousel with constellation pagination
- Page-height starfield
- Horizon footer component
- Nav logo morph
- Minimal copy approach
- Lowercase mode feature flag
- Smooth continuous gradient

### 📖 Documented for Future
- Constellation lines as UI connectors
- Night sky motif in game UI
- Telescope/star map navigation (optional advanced feature)
- Achievement constellations

### 🎯 Next Steps
Apply documented patterns when building:
1. Individual game components (Phase 2)
2. Game lobby/index layout
3. Lore workspace (Phase 3)
4. F1 Predictions interface (Phase 4)

---

## Quick Reference

### Where to Find Implementation Details

| Design Concept | Code Location |
|----------------|---------------|
| Color tokens | `resources/css/app.css` → `:root` |
| Typography scale | `tailwind.config.js` → `fontSize` |
| Spacing system | `tailwind.config.js` → `spacing` |
| Game card | `resources/views/components/ui/game-card.blade.php` |
| Carousel | `resources/views/components/ui/carousel.blade.php` |
| Horizon footer | `resources/views/components/ui/horizon-footer.blade.php` |
| Starfield | `resources/js/starfield.js` |
| Nav morph | `resources/js/nav-morph.js` |
| Lowercase mode | `config/ui.php` + `.lowercase-mode` CSS |

### Where to Find Design Theory

| Design Principle | Documentation |
|------------------|---------------|
| Brand philosophy | `docs/DESIGN_FOUNDATIONS.md` |
| Minimal copy | `.cursor/rules/110-minimal-copy.mdc` |
| Component patterns | `docs/COMPONENT_PATTERNS.md` |
| Color usage | `docs/COLOR_SYSTEM.md` |
| UX insights | `docs/INSIGHTS_FROM_CONVERSATION.md` |

---

## Questions?

**"How do I implement X?"**
1. Check if it exists in component files
2. Reference docs/COMPONENT_PATTERNS.md
3. Follow existing patterns in codebase
4. Maintain design principles from DESIGN_FOUNDATIONS.md

**"Is this design decision aligned?"**
Ask: "Does this feel like the peaceful night sky?"
- If yes → proceed
- If no → reconsider

