# Ursa Minor — Design Bible

*The complete design reference for Ursa Minor Games*

---

## The Peaceful Night Sky

**This is the most important principle**: Everything should feel like the peaceful, quiet, soothing yet joyful night sky.

Ursa Minor isn't a theme — it's a design philosophy. The website is a serene night sky. Everything you add should feel like a star in a constellation or the horizon. Never noise.

### Core Feeling

Calm · Collected · Reserved · Peaceful · Welcoming · Humble · Confident (but never loud)

This site doesn't sell or shout. It simply exists — like the night sky after sunset: still, vast, quietly inspiring. Visitors wander and discover, like stargazers tracing constellations.

---

## Brand Origin Story

### The Name

**Ursa Minor** — the Little Bear constellation. Evokes navigation, discovery, quiet power.

**Personal Connection**: The developer grew up homeschooled at "Ursa Minor Academy" with the motto "The sky is the limit." Nickname: "Little Bear." This makes Ursa Minor deeply personal — a constellation of family history and creative ambition. While the Hitchhiker's Guide reference is nice, this is its own story.

### Tagline

**Primary**: "The sky is the limit."

**Alternates**:
- "Small games. Big craft."
- "Little Bear. Big craft."

---

## The Peaceful Chair Philosophy

**Imagine**: You've taken up a chair to look at the stars. You're enjoying yourself peacefully. If others want to join, they can. But you're not advertising. You're not selling. You're just... there. Peaceful.

**This is Ursa Minor**:
- **No monetization pressure** - No revenue model yet, so more visitors = higher bills. We're not incentivized to optimize engagement. This keeps us honest.
- **Craigslist-like simplicity** - Links you need are there. No ads, no flashy banners, no complications.
- **No attention demands** - Enough things online demand attention. This shouldn't be one of them.
- **Welcoming without selling** - Stay, explore, play. Never pressured or marketed to.
- **Understated, never overstated** - Say what's true, clearly and simply.

This directly informs every UX decision: less scrolling, less reading, less cognitive load, more peace.

---

## Visual Identity

### Colors (HSL System)

**2 Neutrals + 2 Brand Colors** — everything else derived:

```css
/* Neutrals */
--ink: 210 25% 96%;           /* #f2f4f8  light text */
--ink-muted: 220 14% 70%;     /* #aeb6c2  muted text */
--space-900: 226 53% 4%;      /* #050914  deepest black */
--space-700: 220 49% 10%;     /* #0a1427  gradient mid-dark */
--space-600: 213 55% 18%;     /* #0e203d  gradient mid-light */
--space-500: 217 62% 25%;     /* #122a50  gradient lightest */

/* Brand */
--star: 48 89% 77%;           /* #f6e08f  primary CTA accent */
--constellation: 212 100% 81%; /* #9ec7ff  secondary accent */

/* Derived (via opacity) */
--surface: var(--ink);         /* use with / 0.04 */
--border: var(--ink);          /* use with / 0.10 */

/* Earth (footer horizon) */
--earth: 135 10% 12%;          /* dark desaturated earth */
--earth-dark: 135 12% 9%;      /* darker silhouette */

/* Sunset (horizon line) */
--sunset: 28 100% 78%;         /* warm line, use with alpha */
```

**60/30/10 Rule**:
- 60% neutral (backgrounds, text)
- 30% secondary (sections, variants)
- 10% accent — `--star` reserved **exclusively** for primary CTAs

**Contrast**: All text meets WCAG AA (≥ 4.5:1)

### Typography (Major Third Scale 1.25)

**Font**: Oswald (Google Fonts) — clean, modern, slightly condensed

**Type Scale** (16px base):
- Body: 1rem (16px) — line-height 1.5, letter-spacing 0
- H6: 1.25rem (20px)
- H5: 1.563rem (25px)
- H4: 1.953rem (31px)
- H3: 2.441rem (39px)
- H2: 3.052rem (49px)
- H1: 3.815rem (61px)

**Headings**: Tighter line-height (1.1-1.2), slight negative letter-spacing

**Line Length**: 20-35em (45-75 characters) for body text

### Spacing (8-Point Grid)

All spacing in multiples of 8: 8, 16, 24, 32, 48, 64, 96px

**Proximity Principle**:
- Intra-group: 16px (inside sections)
- Inter-group: 32-48px (between sections)
- Breathing room: Always more space around groups than within

### Logo

Custom SVG: Bear with Ursa Minor constellation. Vector only, never rasterize. Responsive sizing via Tailwind.

---

## Layout Principles

### Max Widths
- Main content: 960px centered
- Article/reading: 35em max line length
- Widescreens: Content centers gracefully

### Structure
- Hero: Minimal — logo + tagline + CTAs only
- Sections: Generous vertical rhythm (8-pt multiples)
- Grid: Visual-first game cards
- Footer: Horizon line with earth silhouette

### Responsiveness
- Laptop/tablet first (primary targets)
- Mobile: Later phase
- Widescreens: Centered, not stretched

---

## Minimal Copy Philosophy

### "Sometimes Less Is More"

**Scrolling**: Less is better  
**Reading**: Reduce cognitive burden  
**Purpose**: Every word serves a function

### Rules

**Hero**: Logo + tagline (max 6 words) + CTAs  
**CTAs**: One word only ("Play", "Browse")  
**Game Cards**: Visual motifs only; titles on hover  
**Footer**: Copyright line only  
**Navigation**: Single-word labels

### Tone

Calm, collected, reserved, welcoming, peaceful, humble. Stated clearly without being shy, anxious, nervous, excited, self-demeaning, or spiritual.

**Think**: Peaceful night sky, Craigslist simplicity, no marketing hype.

### Banned on Public Pages
- Future café/shop/storefront promises
- "Years away" timelines
- Marketing superlatives
- Filler text

---

## Component Patterns

### Visual-First Game Cards

**Default**: Visual motif (board pattern, glyph, icon)  
**Hover/Focus**: Title reveals in translucent pill  
**Accessibility**: Full aria-label, sr-only heading  
**Clickable**: Entire card, immediately (no animation wait)  
**Target**: 44px minimum

### Embla Carousel

**Pagination**: Constellation-style — dots as stars with connecting lines  
**Navigation**: Glass arrows with subtle hover  
**Motion**: Respects `prefers-reduced-motion`

### Horizon Footer

**Structure**: Sunset line + earth silhouette + back-to-top  
**Colors**: Warm sunset (low opacity), dark earth  
**Button**: On sky side (above horizon), star icon, glass style  
**Spacing**: ≥64px gap above footer

### Starfield Background

**Canvas**: Sized to document scrollHeight (not viewport)  
**Stars**: 120-350 based on area (~0.00015 per px²)  
**Twinkle**: Gentle opacity drift, 0.1-0.3Hz  
**Performance**: requestAnimationFrame, debounced resize  
**Accessibility**: Static render for `prefers-reduced-motion`

---

## Interactions

### Motion Discipline

**Hover**: Subtle lift (-2px), scale (1.02), or border color change  
**Focus**: Visible rings using `--constellation` color  
**Transitions**: 150-220ms ease  
**Animations**: Gentle, purposeful, never aggressive  
**Accessibility**: Disable motion for `prefers-reduced-motion`

### Affordances

All interactive elements must have:
- Clear hover/focus states
- Keyboard navigation
- Visible focus rings
- 44px minimum touch target
- Immediate clickability

---

## Accessibility (WCAG AA Minimum)

### Contrast
- Normal text: ≥ 4.5:1
- Large text: ≥ 3:1
- UI components: ≥ 3:1

### Semantic HTML
- Proper heading hierarchy (H1 → H2 → H3)
- ARIA labels where needed
- Alt text for images
- Screen reader support

### Keyboard Navigation
- All interactive elements focusable
- Logical tab order
- Visible focus indicators
- Skip to content link (when needed)

### Motion
- Respect `prefers-reduced-motion`
- Disable animations when set
- Provide static alternatives

---

## Content Guidelines

### Public vs Internal

**Public Pages** (homepage, games, about):
- Focus on what exists now
- Brief mission OK, but not centered
- No detailed future café/shop plans

**Internal Docs** (README, docs/):
- Long-term vision OK
- Detailed roadmaps fine
- Technical language acceptable

### Copy Tone

**Do**: Peaceful, confident, welcoming, clear, truthful  
**Don't**: Hype, spiritual, anxious, salesy, apologetic

**Examples**:
- ✅ "Small games. Big craft."
- ✅ "The sky is the limit."
- ❌ "The Ultimate Gaming Experience Awaits!"
- ❌ "Join Our Amazing Journey!"

---

## Technical Standards

### Stack
- Laravel 12 + TALL (Tailwind, Alpine, Laravel, Livewire)
- Flux UI (free tier)
- Heroicons
- Embla Carousel

### Development
- Laravel Herd: http://website.test/
- `npm run dev` (runs in background)
- Railway auto-deploys from main

### Code Quality
- Laravel Pint for formatting
- 80%+ test coverage target
- Conventional commits
- One feature at a time

---

## Design Decision Filter

**Before adding anything, ask**:
1. Does this feel like the peaceful night sky?
2. Does this belong like a star in a constellation, or is it noise?
3. Is this calm, collected, and welcoming?
4. Would this exist quietly and confidently, without shouting?

**If no to any: Don't add it.**

---

## Non-Negotiables

- ✅ Dark theme with correct contrast
- ✅ Centered layouts (960px max)
- ✅ Minimal copy
- ✅ Visual-first approach
- ✅ Calm, peaceful aesthetic
- ✅ Component reuse
- ✅ Accessibility (WCAG AA)
- ✅ No emoji, purple gradients, or trendy flourishes
- ✅ Verification before claiming success

---

## Summary

This is the **design constitution** for Ursa Minor. Every page, component, and visual decision flows from these principles.

Whether changes are made by human or AI, they must align with this foundation.

The test: **Does it feel like the peaceful night sky?**

If yes → proceed. If no → reconsider.

