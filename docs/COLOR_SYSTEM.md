# HSL Color System

Ursa Minor uses an HSL-based color system with CSS variables for flexibility and derived tokens.

## Why HSL?

HSL (Hue, Saturation, Lightness) is used instead of Hex/RGB because:
- More intuitive for humans (adjust lightness/saturation independently)
- Easy to derive shades by rotating hue toward brighter colors
- Supports Tailwind's `<alpha-value>` syntax: `hsl(var(--ink) / .5)`
- Prevents washed-out colors when adjusting brightness

## Core Token Structure

### Root Variables

All color tokens are defined as HSL triplets in `:root`:

```css
:root {
  /* --- Neutrals --- */
  --ink: 210 25% 96%;          /* #f2f4f8  light text */
  --ink-muted: 220 14% 70%;    /* #aeb6c2  muted text */

  /* Dark sky band (top → bottom variants) */
  --space-900: 226 53% 4%;     /* ~#050914 */
  --space-700: 220 49% 10%;    /* ~#0a1427 */
  --space-600: 213 55% 18%;    /* ~#0e203d */
  --space-500: 217 62% 25%;    /* ~#122a50 */

  /* --- Brand --- */
  --star: 48 89% 77%;          /* #f6e08f  (primary CTA accent) */
  --constellation: 212 100% 81%;/* #9ec7ff  (secondary accent) */

  /* --- Surfaces & lines (derived via opacity) --- */
  --surface: var(--ink);        /* use with / 0.04 */
  --border:  var(--ink);        /* use with / 0.10 */

  /* --- Earth / horizon silhouette --- */
  --earth: 135 10% 12%;         /* #1a1f1a */
  --earth-dark: 135 12% 9%;     /* #141814 */

  /* --- Sunset glint at horizon --- */
  --sunset: 28 100% 78%;        /* soft warm line; use with alpha */
}
```

## 2 Neutrals + 2 Brand Colors Rule

**Core Palette (4 colors only)**:
1. **Ink** (neutral light) - Primary text
2. **Space** (neutral dark) - Backgrounds
3. **Star** (brand accent) - Primary CTAs only
4. **Constellation** (brand secondary) - Links, accents

All other colors are **derived** via:
- Opacity modulation (`hsl(var(--ink) / .5)`)
- Lightness changes (dark/light variants in --space family)
- No new hues introduced

## Usage Patterns

### Direct Color Application

```css
/* Body text */
color: hsl(var(--ink));

/* Muted text */
color: hsl(var(--ink-muted));

/* Background */
background: hsl(var(--space-900));
```

### With Opacity

```css
/* Glass surface */
background: hsl(var(--surface) / .04);

/* Border */
border-color: hsl(var(--border) / .10);

/* Semi-transparent overlay */
background: hsl(var(--ink) / .24);
```

### Gradients

```css
/* Page background (continuous, no fold line) */
body {
  background:
    radial-gradient(2px 2px at 20% 30%, hsl(var(--ink) / .07) 0, transparent 60%),
    radial-gradient(1px 1px at 80% 60%, hsl(var(--ink) / .05) 0, transparent 60%),
    linear-gradient(180deg,
      hsl(var(--space-900)) 0%,
      hsl(var(--space-700)) 38%,
      hsl(var(--space-600)) 70%,
      hsl(var(--space-500)) 100%
    );
}
```

### Sunset Horizon Line

```css
.um-horizon-line {
  height: 1px;
  background: linear-gradient(
    90deg,
    hsl(var(--sunset) / 0) 0%,
    hsl(var(--sunset) / .45) 50%,
    hsl(var(--sunset) / 0) 100%
  );
}
```

## Tailwind Integration

Map tokens to Tailwind classes in `tailwind.config.js`:

```js
module.exports = {
  theme: {
    extend: {
      colors: {
        ink:           "hsl(var(--ink) / <alpha-value>)",
        "ink-muted":   "hsl(var(--ink-muted) / <alpha-value>)",
        space: {
          900: "hsl(var(--space-900) / <alpha-value>)",
          700: "hsl(var(--space-700) / <alpha-value>)",
          600: "hsl(var(--space-600) / <alpha-value>)",
          500: "hsl(var(--space-500) / <alpha-value>)",
        },
        star:          "hsl(var(--star) / <alpha-value>)",
        constellation: "hsl(var(--constellation) / <alpha-value>)",
        surface:       "hsl(var(--surface) / <alpha-value>)",
        border:        "hsl(var(--border) / <alpha-value>)",
        earth:         "hsl(var(--earth) / <alpha-value>)",
        "earth-dark":  "hsl(var(--earth-dark) / <alpha-value>)",
        sunset:        "hsl(var(--sunset) / <alpha-value>)",
      },
    }
  },
}
```

### Tailwind Usage

```blade
{{-- Text colors --}}
<p class="text-ink">Primary text</p>
<p class="text-ink/80">80% opacity text</p>
<p class="text-ink-muted">Muted text</p>

{{-- Backgrounds --}}
<div class="bg-space-900">Deep space</div>
<div class="bg-[hsl(var(--surface)/.04)]">Glass surface</div>

{{-- Borders --}}
<div class="border border-[hsl(var(--border)/.10)]">Card</div>

{{-- CTAs (reserve star for primary actions only) --}}
<button class="bg-star text-[hsl(220_20%_10%)] hover:bg-star/90">
  Play
</button>
```

## Color Roles and Rules

### Accent Color Usage (10% Rule)

**`--star` (Soft star yellow)**:
- **ONLY** for primary CTAs ("Play", "Browse")
- Reserved exclusively to signal action
- Never use for decoration or secondary elements
- Maintains intrinsic user recognition

**`--constellation` (Cool blue)**:
- Secondary accent for links
- Active states
- Focus indicators
- Use sparingly

### Surface & Border Tokens

These are **derived** from `--ink` with low opacity:

```css
/* Glass card */
.um-card {
  background: hsl(var(--surface) / .04);  /* 4% white */
  border: 1px solid hsl(var(--border) / .10); /* 10% white */
}
```

### Earth Tones

Used **only** for horizon footer:

```css
.um-horizon-silhouette {
  background: linear-gradient(to bottom, 
    hsl(var(--earth) / .96), 
    hsl(var(--earth-dark) / 1));
}
```

## WCAG Contrast Requirements

All text must meet **WCAG AA** (≥ 4.5:1 for normal text).

### Pre-Approved Ratios

| Foreground | Background | Ratio | Status |
|------------|------------|-------|--------|
| `--ink` | `--space-900` | 21:1 | ✅ AAA |
| `--star` | `--space-900` | ~18:1 | ✅ AAA |
| `--ink-muted` | `--space-600` | ≥ 4.5:1 | ✅ AA |

### Checking New Combinations

Use WebAIM Contrast Checker or DevTools Accessibility panel:

```css
/* ✅ PASS - High contrast */
color: hsl(var(--ink));
background: hsl(var(--space-900));

/* ⚠️ CHECK - Ensure muted text still passes */
color: hsl(var(--ink-muted));
background: hsl(var(--space-600));

/* ❌ FAIL - Too low contrast */
color: hsl(var(--ink) / .3);
background: hsl(var(--space-900));
```

## Examples

### Button Styles

```css
/* Primary CTA */
.btn-primary {
  background: hsl(var(--star));
  color: hsl(220 20% 10%); /* Dark text on warm accent */
}

.btn-primary:hover {
  background: hsl(var(--star) / .92);
}

/* Secondary button */
.btn-secondary {
  background: transparent;
  border: 1px solid hsl(var(--border) / .2);
  color: hsl(var(--ink));
}
```

### Card Component

```css
.um-game-card {
  background: hsl(var(--surface) / .04);
  border: 1px solid hsl(var(--border) / .10);
  border-radius: 1rem;
  backdrop-filter: blur(4px);
}

.um-game-card:hover {
  border-color: hsl(var(--border) / .20);
  transform: translateY(-2px);
}
```

### Focus States

```css
:focus-visible {
  outline: 2px solid hsl(var(--constellation));
  outline-offset: 2px;
}
```

## Migration from Hex Values

### DO NOT hardcode hex in views

```blade
{{-- ❌ WRONG --}}
<div style="color: #f2f4f8; background: #050914;">

{{-- ✅ CORRECT --}}
<div class="text-ink bg-space-900">
```

### Replace inline rgba with HSL tokens

```css
/* ❌ OLD - Hardcoded rgba */
background: rgba(255, 255, 255, 0.05);
border: 1px solid rgba(255, 255, 255, 0.1);

/* ✅ NEW - HSL tokens */
background: hsl(var(--surface) / .05);
border: 1px solid hsl(var(--border) / .1);
```

## Starfield Colors

In `starfield.js`, use tokens via CSS variable interpolation:

```javascript
// ❌ AVOID - Hardcoded
ctx.fillStyle = 'rgba(255, 255, 255, 0.6)'

// ✅ PREFER - Token reference
const inkColor = getComputedStyle(document.documentElement)
  .getPropertyValue('--ink')
  .trim()
ctx.fillStyle = `hsl(${inkColor} / 0.6)`
```

Or keep simple and use the known value since starfield is performance-critical:

```javascript
// ✅ ACCEPTABLE for performance-critical canvas
ctx.fillStyle = `rgba(242, 244, 248, ${alpha})`
```

## Design Principles

1. **Limit the palette** - Only 4 core colors; derive everything else
2. **HSL for flexibility** - Easy to create variants without looking washed out
3. **Opacity for depth** - Use alpha channel for surfaces, borders, overlays
4. **Semantic naming** - Token names describe purpose, not appearance
5. **Maintain contrast** - Always verify WCAG AA minimum

## Questions?

When working with colors:
- Use HSL tokens, never hardcoded hex in views
- Reserve `--star` for primary CTAs only
- Derive surfaces/borders from `--ink` with low opacity
- Check contrast ratios for accessibility
- Keep the palette minimal and intentional

