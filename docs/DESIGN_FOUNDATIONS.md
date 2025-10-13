# 🌌 Ursa Minor — Design Foundations Document
_Last updated: {{DATE}}_

---

## 1. Purpose & Vision

Ursa Minor is a small but ambitious game studio in its early stages, laying the groundwork for a long-term creative and commercial journey.

Right now, our focus is **not** on selling products or operating a physical space. Instead, our goals are to:

- **Establish a strong, professional brand presence online** through a polished website and consistent design language.  
- **Build experience and frameworks** by releasing small browser games and internal tools.  
- **Show early tangible output** — playable games, lore content, a professional web presence — to ourselves, close family, and eventually a broader community.  
- **Create a foundation** for larger projects down the line, such as original board games, a deckbuilding video game, and possibly a future board game café in New Zealand.

We are playing the long game. The site we build now is not about making promises years out, but about doing **one thing well at a time**.

---

## 2. Current Stage

We are at **Stage 1 — Brand & Foundations**:

- The homepage is the first focus. It sets the tone for everything else.  
- We are using the **TALL stack** (Tailwind, Alpine, Laravel, Livewire) with additional libraries like Pint, Heroicons, and **Flux UI** for component consistency.  
- Hosting is on **Railway**, targeting laptop and large tablet screens first. Mobile support will come later.  
- All games and content are developed in-house by a single developer, with occasional contributions from close family.

---

## 3. Core Intentions

These intentions guide every design and UX decision:

- **Professionalism over flash**: The site should feel stable, trustworthy, and deliberate, not gimmicky or experimental.  
- **Calm clarity**: Inspired by the night sky — quiet confidence, clean lines, balanced whitespace, subtle animation.  
- **Timelessness**: No trendy gradients or fleeting UI fashions. This should still feel good 5+ years from now.  
- **Incremental build**: Launch simple and solid, then expand steadily.  
- **Truthful communication**: No overpromises about future shops, products, or timelines. What we show is what exists.

---

## 4. Brand Identity

### 4.1 Brand Name & Origin Story

**Ursa Minor** — evokes constellations, navigation, and discovery. Quiet power, not loud spectacle.

**Personal Connection**: The name comes from a deeply personal place. Growing up homeschooled, our family school was registered as "Ursa Minor Academy" with the slogan "The sky is the limit." The developer's nickname was "Little Bear," making Ursa Minor (the Little Bear constellation) a natural fit. While the Hitchhiker's Guide to the Galaxy reference is a nice bonus, this is its own story—a constellation of personal history and creative ambition.

This origin story informs our approach: we're building something personal, meaningful, and quietly ambitious. Not a loud commercial venture, but a patient craft that grows under the stars.

### 4.2 Logo & Lockup
- Custom SVG of a **bear with the Ursa Minor constellation** inside.  
- Used in hero sections, navigation, and select branding placements.  
- Scaling: Prefer vector scaling in CSS; never rasterize. Use Tailwind classes for responsive sizing.

### 4.3 Visual Style

| Element | Value |
|---|---|
| **Primary Background** | Deep night gradient: `#050914 → #0b1a33` |
| **Text (Light)** | `#f2f4f8` (primary), `#aeb6c2` (muted) |
| **Accent** | Soft star yellow `#f6e08f` |
| **Secondary Accent** | Constellation blue `#9ec7ff` |
| **Surfaces** | Translucent glass: `rgba(255,255,255,0.04)` background with subtle border |
| **Fonts** | Clean, modern sans-serif with good readability (no gimmicks) |
| **Icons** | Heroicons outline set; match stroke weight to text size |

### 4.4 Thematic Inspiration

- **Night sky** as a metaphor: infinite, quiet, beautiful.  
- **Stars and constellations** are decorative but never overpowering.  
- **Motion is minimal and meaningful** — e.g., subtle twinkle, gentle hover transitions.

---

## 5. UX & UI Principles

### 5.1 Layout & Structure

- **Max width**: All main content centered in a `.section` container, max width ~960 px.  
- **Responsiveness**: Laptop and large tablet are first-class citizens; content should gracefully center on widescreens.  
- **Hero**: Centered, minimal copy approach. Logo + headline + two one-word CTAs only. No body text unless essential.
- **Card grid**: Visual-first game cards showing motifs/glyphs by default. Titles reveal on hover/focus. Carousel on homepage, grid on games index.
- **Footer**: Truly minimal - just copyright line. Horizon line with back-to-top button.

### 5.2 Minimal Copy Philosophy

**"Sometimes less is more"** — The site embraces restraint and visual clarity:

- **Minimal visible copy**: Reduce reading burden. Let visuals communicate where possible.
- **One thing well at a time**: Don't overwhelm with options or information.
- **Scrolling discipline**: Keep above-the-fold content focused. Less scrolling = better experience.
- **Purposeful text**: Every word serves a function. No filler text.

**Tone**: Peaceful, confident, welcoming. Not hype, not apology, not spiritual language. Calm, collected, reserved, humble yet stated clearly. Think: the peaceful feeling of looking at the night sky after sunset.

### 5.3 Color & Contrast

- **Dark theme by default**.  
- Text must meet **WCAG AA contrast**: 4.5:1 minimum.  
- Accent colors must never be used as text on light backgrounds.  
- Light-on-dark or dark-on-light is strictly controlled — no gray-on-gray low contrast.

### 5.4 Visual-First Game Cards

Game cards prioritize visual recognition over text labels:

- **Motifs**: Each game has a unique visual motif (board pattern, glyph, icon) that communicates the game type
- **Hover reveal**: Title appears in a translucent pill on hover/focus for identification
- **Accessibility**: Full accessible text via `aria-label` and screen-reader-only heading; keyboard focus mirrors hover
- **Click area**: Entire card is clickable at all times (no wait for hover)
- **Aspect ratio**: Consistent 3:2 ratio maintains grid alignment
- **Touch targets**: 44px minimum for accessibility compliance

### 5.5 Iconography

- Icons must scale proportionally with text.  
- In game cards, motifs are decorative but semantically meaningful.
- Visual motifs should be recognizable and distinct across games.
- Default size: 1.25rem–1.5rem for UI icons, larger (80-120px) for game motifs.

### 5.6 Interaction

- All interactive elements must have clear affordances, hover/focus states, and be keyboard navigable.  
- **Motion discipline**: Subtle lift/fade on hover/focus; immediate clickability (no delays).
- Motion respects `prefers-reduced-motion`.  
- Subtle transitions only — no bouncing, sliding, or rotating.
- **Focus-visible rings**: Use constellation blue for focus indicators.

### 5.7 Tone & Voice

- Copy is concise, neutral, and truthful.  
- Public pages **must not** reference distant future plans like cafés or big game launches.  
- Brand voice: **peaceful, confident, welcoming**. Not hype, not anxious, not spiritual.
- Design and copy should be **calm, collected, reserved, welcoming, humble** — stated without being shy.
- The entire site brings a tone of **joyful zen** — like looking at the night sky and finding peace.

### 5.8 The "Peaceful Chair" Philosophy

**Imagine**: You've taken up a chair to look at the stars. You're enjoying yourself, peacefully present. If others want to join you they can, but you're not advertising. You're not selling. You're not demanding attention. You're just... there. Peaceful.

This is the essence of Ursa Minor's web presence:
- **No monetization pressure** - Currently no revenue model, so more visitors = higher bills. We're not incentivized to optimize engagement or retention. This keeps us honest.
- **Craigslist-like simplicity** - Like Craigslist, the links you want are there, but no advertisements, no flashy banners, no over-complicated anything. Just what's needed.
- **No attention demands** - There are enough things online demanding attention; this site shouldn't be one of them.
- **Welcoming without selling** - You're invited to stay, explore, play, but never pressured or marketed to.
- **Understated or homo-stated, never overstated** - Say what is true, clearly and simply, without embellishment.

This philosophy directly informs every UX decision: less scrolling, less reading, less cognitive load, more peace.

---

## 6. Architectural Principles

- Use Laravel conventions: Models in `app/Models`, Livewire in `app/Http/Livewire`, Blade components in `resources/views/components`.  
- Livewire handles interactivity; Blade handles structure and presentation.  
- UI strings live in `lang/en` files for future localization.  
- Components (e.g., logo-lockup, card, section-header) must be reused, not reinvented per page.

---

## 7. Testing & Guardrails

- Homepage must always render correctly: hero, card grid, footer, nav.  
- Simple smoke tests (Laravel feature tests or Cypress) verify presence and structure.  
- No public page should contain banned future-facing words (“café”, “shop”, “years away”).  
- Large images above the fold are disallowed — starfield must be CSS.

---

## 8. Roadmap Snapshot

| Phase | Goal | Deliverables |
|---|---|---|
| 1 | Foundation | Homepage, basic games, blog, rules |
| 2 | Lore Workspace | Private wiki for contributors |
| 3 | User Accounts | Save state, dashboards |
| 4 | Predictions Game | Web version of F1 predictions |
| 5 | E-Commerce | Print-and-play |
| 6 | Game Prototype | Vertical slice of “Agency” |
| 7 | Physical | Future shop/café (off-site, long term) |

---

## 9. Design Non-Negotiables

- ✅ Dark theme with correct contrast  
- ✅ Centered layouts  
- ✅ **Minimal copy**: Reduce text, increase visual clarity
- ✅ **Visual-first approach**: Let imagery and motifs communicate
- ✅ Professional tone and content honesty  
- ✅ **Calm, peaceful aesthetic**: Not hype, not anxious, welcoming without spiritual language
- ✅ Component reuse  
- ✅ No random gradients, emojis, or trendy flourishes  
- ✅ **Accessibility**: 44px touch targets, keyboard navigation, screen reader support, focus states
- ✅ Verification > assertion — agents must check their work

---

## 10. Audience

Primary:  
- **Myself** — to prove viability and build experience.  
- **Close family** — to showcase tangible progress.

Secondary:  
- **Browser gamers** and design peers, as we grow.

---

## 11. Summary

This document is the **North Star** for all design, UX, and brand decisions in the Ursa Minor project. Whether changes are made by a human designer, developer, or AI agent, they must align with this foundation.

This is not a marketing document — it’s a **design constitution**. Every future page, component, and visual decision should flow from these principles.
