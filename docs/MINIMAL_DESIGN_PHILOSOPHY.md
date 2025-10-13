# Minimal Design Philosophy — Ursa Minor Games

_Last updated: 2025-10-13_

---

## Core Principle: Less is More

The Ursa Minor website embraces a **minimal-copy, visual-first** approach that prioritizes calm clarity over information density.

### Why Minimal?

**Less scrolling. Less reading. Less searching. More experiencing.**

Every element on the site should serve a purpose. No text just for text's sake. This is the subtle and difficult art of simplicity.

---

## Design Intent

### The Feeling We're Creating

**Peaceful. Confident. Welcoming.**

Without:
- Hype or excitement
- Apology or self-deprecation  
- Spiritual language or mysticism
- Anxiety or nervousness

With:
- Calm, collected presentation
- Reserved yet clear statements
- Humble without being shy
- Welcoming without being pushy

**Inspiration**: The joyful zen of looking at the night sky after sunset and finding peace.

---

## Simplicity ∩ Night Sky

Simplicity should not diminish the night sky theme — they should **comingle** for a truly inspired and cohesive site.

- The starfield remains, but subtle
- The gradient stays, but doesn't overwhelm
- Visual motifs communicate without words
- Space (whitespace) is intentional and generous

**Not brutalism**. Not sterile minimalism. **Joyful zen** — websites as art, an expression of heart.

---

## Practical Application

### Homepage Hero

**Before**: Logo + kicker ("A small game studio") + headline + body paragraph + two CTAs  
**After**: Logo + tagline ("The sky is the limit") + two one-word CTAs

**Removed**:
- Kicker text ("A small game studio")
- Headline ("Small games. Big craft" - too self-aggrandizing)
- Hero body text ("We build elegant, replayable games..." - too internal)
- Descriptive labels on buttons ("Play a game" → "Play")
- Decorative right column

**Added**:
- Tagline respects lowercase mode feature flag
- Even simpler, more peaceful presentation

**Result**: Ultimate simplicity. Just logo, tagline, and actions.

---

### Games Grid

**Before**: Cards with title, description, icon, "Play Now" button  
**After**: **Square** visual motifs only, titles grow upward on hover/focus

**Approach**:
- **Square cards** (1:1 aspect ratio) - most games are square-based (tic-tac-toe, chess, sudoku, etc.)
- Each game has a **distinct visual motif** (board pattern, glyph, icon)
- Motifs are **recognizable without labels**
- Hovering/focusing **grows a bar upward from the bottom** showing the title
- Entire card is clickable — no hunting for buttons
- Screen readers get full accessible text via `aria-label`
- **No descriptions** - show, don't tell

**Grid Layout**:
- 2 columns on mobile
- 3 columns on tablet (sm)
- 4 columns on desktop (lg)

**Result**: Pure visual recognition. Show the game, don't describe it. Immediate action.

---

## Copy Guidelines

### What We Removed

❌ **Kicker text**: "A small game studio" (too self-defining)
❌ **Hero headline**: "Small games. Big craft." (too self-aggrandizing)  
❌ **Body text**: "We build elegant, replayable games..." (too internal, belongs in docs)
❌ **Section descriptions**: Removed "Available now" kicker and subtitle (too demanding)
❌ **Verbose CTAs**: "Play a game" became "Play"  
❌ **Game descriptions**: Show the game visually, don't describe it
❌ **Blog section**: Removed entirely (user unsure about blog)

### What We Kept

✅ **Tagline**: "The sky is the limit" (respects lowercase mode)
✅ **Game titles**: Available on hover/focus for identification  
✅ **Accessibility text**: Full context for screen readers
✅ **Footer note**: Single poetic line

### Writing Principles

1. **Every word must earn its place**
2. **If a visual can communicate it, let it**
3. **One action per section** — don't overwhelm with choices
4. **Clarity over cleverness**
5. **Confidence without arrogance**

---

## Accessibility Without Compromise

Minimal design **does not mean** removing accessibility features:

### What We Added

✅ **aria-label** on every game card with full context  
✅ **sr-only headings** for screen readers  
✅ **44px minimum touch targets** on all interactive elements  
✅ **Focus-visible rings** on all focusable elements  
✅ **Keyboard navigation** mirrors hover reveals  
✅ **Reduced motion support** for animations

### The Contract

**Visual simplicity for sighted users. Full context for screen readers.**

The `.sr-only` class and `aria-label` attributes provide complete information to assistive technology while keeping the visual interface clean.

---

## Motion Discipline

Animations should be **subtle, purposeful, and respectful**:

### Our Rules

- **Hover effects**: Gentle lift (2px) + fade-in title reveal
- **Transitions**: 150ms ease-out (quick, not jarring)
- **No waiting**: Clickability is immediate, not dependent on hover state
- **Reduced motion**: Full support via `prefers-reduced-motion` media query

### What We Avoid

❌ Bouncing, rotating, or sliding effects  
❌ Long animation durations (> 300ms)  
❌ Animation-dependent functionality  
❌ Distracting or attention-grabbing motion

---

## Visual Motifs

Each game has a unique, recognizable visual:

| Game | Motif | Visual Communication |
|------|-------|---------------------|
| Tic-Tac-Toe | 3×3 grid lines | Classic grid pattern |
| Connect 4 | Circular grid | Falling discs concept |
| Sudoku | 9×9 grid with bold separators | Puzzle grid |
| Minesweeper | Mine with spikes | Danger/reveal concept |
| Snake | Curved path with dot | Movement/path |
| 2048 | Tile grid | Sliding tiles concept |

**Design principle**: Motifs should be instantly recognizable by players familiar with the games.

---

## Spacing & Rhythm

Following an **8-point grid system**:

### Inter-Section Spacing (Between major sections)
- Desktop: 48-64px (`py-12 md:py-16`)
- Mobile: 48px minimum

### Intra-Section Spacing (Within sections)
- Elements: 16-24px (`gap-4 md:gap-6`)
- Typography: Consistent with type scale

### Breathing Room
- Around hero: Generous (80-128px top padding)
- Around cards: Moderate (16-24px gaps)
- Around text: Comfortable line-height (1.5)

**Goal**: Comfortable reading and scanning without feeling cramped or sparse.

---

## Testing Checklist

When implementing minimal design:

### Visual
- [ ] Can the purpose be understood without reading?
- [ ] Are interactive elements immediately obvious?
- [ ] Does it feel calm and uncluttered?
- [ ] Is whitespace intentional, not accidental?

### Functional
- [ ] Can users complete primary actions in 1-2 clicks?
- [ ] Do hover states provide helpful context?
- [ ] Are touch targets 44px minimum?
- [ ] Does keyboard navigation work perfectly?

### Accessibility
- [ ] Screen readers get full context?
- [ ] Focus indicators clearly visible?
- [ ] Reduced motion respected?
- [ ] Color contrast meets WCAG AA?

### Tone
- [ ] Does it feel peaceful and welcoming?
- [ ] Is the voice confident without hype?
- [ ] Are we respecting the visitor's time and attention?

---

## Anti-Patterns to Avoid

### ❌ Don't

- Add text "just in case" — trust the visual design
- Use long button labels when one word will do
- Repeat information already communicated visually
- Add section headers if the content is self-evident
- Apologize or over-explain ("We're just a small studio...")
- Get spiritual ("Journey with us under the stars...")

### ✅ Do

- Let visuals speak first, text second
- Use hover reveals to progressively disclose
- Trust that users understand common patterns
- Provide full context in accessible text
- State facts with quiet confidence
- Maintain the night sky aesthetic throughout

---

## Evolution

This philosophy will evolve as the site grows:

### Current Stage (Phase 1)
- Homepage minimal and focused
- Games grid visual-first
- Single tagline, no manifesto

### Future Considerations
- Blog may have more text (expected)
- About page can tell our story (appropriate)
- Game instructions need detail (functional)
- Lore content can be rich (intentional)

**The principle remains**: In public-facing, first-impression pages, **less is more**.

---

## Summary

Ursa Minor's minimal design philosophy:

1. **Reduce cognitive load** through visual-first design
2. **Respect visitor attention** with purposeful copy
3. **Maintain accessibility** without visual compromise
4. **Create peaceful experience** through calm aesthetics
5. **Let night sky theme shine** through intentional simplicity

**Not minimalism for aesthetics. Minimalism for **experience****

The goal is for visitors to feel the same peace we feel when looking at the stars.

---

**Questions?** This is a living philosophy. Discuss with the development team.

**Built under the stars** | **© 2025 Ursa Minor Games**

