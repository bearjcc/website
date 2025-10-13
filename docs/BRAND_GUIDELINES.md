# Ursa Minor Games - Brand Guidelines

## Brand Overview

**Ursa Minor Games** is a game development brand inspired by the simple elegance of the night sky. We create memorable gaming experiences ranging from classic browser games to innovative board games and ambitious video game projects.

**Mission**: To build elegant, replayable games and share the journey.

**Tagline**: "The sky is the limit."

**Alternate**: "Small games. Big craft."

## Visual Identity

### Theme & Inspiration

Our brand aesthetic draws from the natural beauty of the night sky:

- **Primary Inspiration**: The transition from deep night to late evening (just after dusk)
- **Elements**: Stars, constellations, moon, celestial space
- **Style**: Clean, minimal, elegant

### What We Embrace

- Night sky gradients (black to deep blue)
- Starfield effects and subtle animations
- Celestial imagery (stars, moon, constellations)
- Minimalist design principles
- Soft yellow accents (like distant starlight)
- Deep blues and blacks
- Occasional sunrise/sunset imagery when appropriate

### What We Avoid

- Purple gradients (AI design cliché)
- Overly complex or busy designs
- Castle or medieval fantasy imagery
- Emoji in production code/branding
- Generic gradient mashups
- Neon colors or harsh contrasts

## Color Palette

### Primary Colors

| Color Name | Hex Code | RGB | Usage |
|------------|----------|-----|-------|
| Night Black | `#000000` | rgb(0, 0, 0) | Deep space, primary background |
| Midnight Blue | `#001a33` | rgb(0, 26, 51) | Mid-gradient, depth |
| Evening Blue | `#002d58` | rgb(0, 45, 88) | Late dusk, lighter sections |
| Star White | `#ffffff` | rgb(255, 255, 255) | Text, stars, highlights |
| Star Yellow | `#fff89a` | rgb(255, 248, 154) | Accents, highlights, CTAs |

### Secondary Colors (Opacity Variations)

| Purpose | Value | Usage |
|---------|-------|-------|
| Dark Overlay | `rgba(0, 0, 0, 0.3)` | Content cards, sections |
| Light Overlay | `rgba(255, 255, 255, 0.05)` | Feature cards, subtle backgrounds |
| Muted Text | `rgba(255, 255, 255, 0.7)` | Secondary text, disclaimers |
| Subtle Accent | `rgba(255, 248, 154, 0.3)` | Borders, dividers |

### Color Usage Guidelines

**Do**:
- Use the full gradient (Night Black → Midnight Blue → Evening Blue) for page backgrounds
- Use Star White for primary text and UI elements
- Use Star Yellow sparingly for accents and important highlights
- Maintain proper contrast ratios for accessibility (WCAG AA minimum)
- Apply backdrop blur effects to cards over gradient backgrounds

**Don't**:
- Mix in off-brand colors (purples, hot pinks, lime greens)
- Use pure black (#000) and pure white (#fff) side-by-side without transition
- Overuse yellow accents (they should feel special)
- Create gradients that don't align with the night sky theme

## Typography

### Font Family

**Primary Font**: [Oswald](https://fonts.google.com/specimen/Oswald) (Google Fonts)
- **Weights Available**: 200, 300, 400, 500, 600, 700
- **Style**: Modern, clean, slightly condensed
- **Fallback Stack**: `'Oswald', sans-serif`

### Type Hierarchy

| Element | Size | Weight | Color | Usage |
|---------|------|--------|-------|-------|
| H1 | 3rem (48px) | 700 | Star White | Page titles |
| H2 | 2.5rem (40px) | 600 | Star Yellow | Section headers |
| H3 | 1.8rem (28.8px) | 500 | Star White | Feature titles |
| Tagline | 1.5rem (24px) | 400 (italic) | Star Yellow | Hero taglines |
| Body | 1.1rem (17.6px) | 400 | Star White | Main content |
| Small | 0.95rem (15.2px) | 400 | Muted Text | Footer, disclaimers |

### Typography Guidelines

**Do**:
- Maintain a clear visual hierarchy
- Use generous line-height (1.8) for body text
- Apply italic style for taglines and emphasis
- Center-align headings for a balanced look
- Use heavier weights for important CTAs

**Don't**:
- Use more than 3 levels of heading on a single page
- Set body text below 1rem
- Use ultra-light weights (200) for critical text
- Create long lines of text (max-width: 800px for readability)

## Logo & Branding Elements

### The Bear Icon

- **File**: `bear.svg`
- **Usage**: Central header logo, represents Ursa Minor (Little Bear constellation)
- **Color**: Monochrome/white
- **Behavior**: Scales down on scroll from 20rem to 3rem height

### Brand Name Treatment

**Full Brand**: "Ursa Minor Games"
- "Ursa" and "Minor" flank the bear icon in the header
- Font size: 6vw at top, scales to 2.5rem on scroll
- Always maintain spacing around the icon

**Short Form**: "Ursa Minor"
- Use when space is limited
- Never abbreviate to "UM" or "UMG"

### Logo Guidelines

**Do**:
- Keep the bear icon centered between "ursa" and "minor"
- Maintain adequate white space around the logo
- Use on dark backgrounds only (it's a white icon)
- Animate smoothly (1s transitions)

**Don't**:
- Stretch or distort the bear icon
- Place on light backgrounds
- Add drop shadows or effects
- Change the icon color

## UI Components

### Cards & Containers

**Feature Cards**:
```css
background: rgba(255, 255, 255, 0.05);
border-left: 4px solid #fff89a;
border-radius: 10px;
padding: 2rem;
backdrop-filter: blur(5px);
```

**Section Containers**:
```css
background: rgba(0, 0, 0, 0.3);
border-radius: 10px;
backdrop-filter: blur(10px);
```

### Buttons & Links

**Primary Button Style** (future implementation):
- Background: Star Yellow (#fff89a)
- Text: Night Black (#000000)
- Border radius: 8px
- Padding: 0.75rem 1.5rem
- Hover: Slight scale (1.05) + box-shadow

**Link Style**:
- Default: Star White
- Hover: Star Yellow
- Transition: 0.2s ease
- No underline by default, underline on hover optional

### Navigation

**Header Navigation**:
- Sticky positioning
- Smooth transitions (1s ease)
- Collapses on scroll from 20vh to 64px
- Links: White text, yellow on hover

**Footer Navigation**:
- Subtle, muted appearance
- Yellow links on dark background
- Border top: 1px solid rgba(255, 248, 154, 0.3)

## Animation & Effects

### Starfield Effect

**Implementation**: `script.js`
- Randomly positioned circles (1-3px)
- 60% of stars blink with random timing
- White base color, yellow blink color
- Density: ~1 star per 10,000 pixels

**Guidelines**:
- Keep animation subtle (1-4 second durations)
- Use random delays for natural feel
- Don't animate too many stars (performance)

### Scroll Animations

**Header Collapse**:
- Transition duration: 1s ease
- Elements scale and reposition smoothly
- Flexbox layout shifts

**Hover Effects**:
- Transform: translateX(10px) on feature cards
- Box shadow: subtle yellow glow
- Duration: 0.3s ease
- Never use bounce or elastic easing

### Backdrop Blur

Applied to cards and sections for depth:
- Feature cards: `blur(5px)`
- Section containers: `blur(10px)`
- Footer: `blur(10px)`

## Content Guidelines

### Tone & Voice

**Brand Voice**: Welcoming, passionate, authentic
- We're enthusiastic about games but not hyperbolic
- We're transparent about our journey and progress
- We're inclusive and community-focused

**Writing Style**:
- Clear and conversational
- Avoid jargon unless necessary
- Use active voice
- Be honest about "coming soon" features

### Content Structure

**Homepage Sections**:
1. Hero (brand intro, tagline)
2. Vision/Mission (our story)
3. Coming Soon (feature previews)
4. Call to Action (community join)

**Feature Descriptions**:
- Start with what it is
- Explain why it matters
- Set clear expectations (especially for "coming soon")

## Accessibility

### Standards

- **Minimum**: WCAG 2.1 Level AA compliance
- **Target**: AAA where feasible

### Implementation

**Color Contrast**:
- Star White on Night Black: 21:1 (AAA)
- Star Yellow on Night Black: 18:1 (AAA)
- Muted Text on backgrounds: Minimum 4.5:1 (AA)

**Semantic HTML**:
- Use proper heading hierarchy (h1 → h2 → h3)
- Include ARIA labels where needed
- Ensure keyboard navigation works
- Provide alt text for images

**Motion**:
- Respect `prefers-reduced-motion` media query
- Provide alternatives to animation-dependent content
- Keep animations smooth and not too fast

## Icon Usage

### Allowed Icon Sets

- **Heroicons** (recommended): https://heroicons.com
- **Feather Icons**: Simple, clean line icons
- **Lucide**: Modern, consistent icon set
- Custom celestial icons (stars, moon, etc.)

### Icon Guidelines

**Do**:
- Use outline style for most icons
- Match icon weight to surrounding text
- Size icons proportionally (1.2-1.5em for inline)
- Keep icon sets consistent across the site

**Don't**:
- Use emoji as icons in production
- Mix icon styles (outline + solid randomly)
- Use overly complex or detailed icons
- Add icons to every heading or paragraph

## Responsive Design

### Breakpoints

| Size | Width | Notes |
|------|-------|-------|
| Mobile | < 768px | Single column, larger touch targets |
| Tablet | 768px - 1024px | Flexible layouts |
| Desktop | > 1024px | Max content width: 1200px |

### Mobile Considerations

- Header scales appropriately (min-height: 64px)
- Touch targets minimum 44x44px
- Readable text without zoom (16px minimum)
- Simplified navigation patterns

## File Organization

### Assets

```
public/
├── bear.svg              # Logo (don't read directly, use cat)
├── GRADIENT BG.svg       # Legacy gradient (now CSS)
├── style.css            # Main stylesheet
├── script.js            # Starfield animation
└── scroll.js            # Header scroll behavior
```

### CSS Structure

Organized by purpose with clear section comments:
```css
/* ===== Base Styles ===== */
/* ===== Reset & Base Styles ===== */
/* ===== Scrollbar Styles ===== */
/* ===== Header & Navigation ===== */
/* ===== Logo & Branding ===== */
/* ===== Main Content ===== */
/* ===== Content Sections ===== */
/* ===== Footer ===== */
/* ===== Effects & Animations ===== */
/* ===== Layout Helpers ===== */
```

## Examples

### Good Design Patterns

**Feature Card**:
```html
<div class="feature">
    <h3>Browser Games</h3>
    <p>Classic games reimagined for the web...</p>
</div>
```

**Section with Background**:
```html
<section class="mission-section">
    <article>
        <h2>Our Vision</h2>
        <p>One day, we dream of...</p>
    </article>
</section>
```

### Anti-Patterns

**❌ Don't**: Inline emoji
```html
<h3>🎮 Browser Games</h3>
```

**✅ Do**: Text only or icons
```html
<h3>Browser Games</h3>
```

**❌ Don't**: Off-brand gradients
```css
background: linear-gradient(purple, pink);
```

**✅ Do**: Night sky gradients
```css
background: linear-gradient(#000000, #002d58);
```

## Implementation Checklist

When adding new pages or features:

- [ ] Use night sky color palette
- [ ] Apply Oswald font
- [ ] Implement starfield effect
- [ ] Add backdrop blur to cards
- [ ] Ensure proper heading hierarchy
- [ ] Test color contrast ratios
- [ ] Verify responsive design
- [ ] Remove any emoji from production code
- [ ] Add smooth transitions
- [ ] Test on multiple browsers
- [ ] Validate accessibility
- [ ] Use semantic HTML

## Version History

- **v1.0** (2025-10-12): Initial brand guidelines established
  - Night sky color palette defined
  - Typography system documented
  - Component patterns established
  - Accessibility standards set

---

**Questions?** Open an issue on GitHub or contact the development team.

**Built under the stars** | **© Ursa Minor Games**

