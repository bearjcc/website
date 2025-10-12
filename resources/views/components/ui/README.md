# UI Components

Reusable Blade components for the Ursa Minor homepage and site-wide use.

## Components

### Logo Lockup (`logo-lockup.blade.php`)
Displays the Ursa Minor logo (bear constellation) with text.

**Usage:**
```blade
<x-ui.logo-lockup class="w-[260px] md:w-[320px]" />
```

**Props:**
- `class` (optional): Custom classes for sizing (default: `w-[260px] md:w-[320px] h-auto`)

---

### Section Header (`section-header.blade.php`)
Displays kicker text, title, and subtitle for content sections.

**Usage:**
```blade
<x-ui.section-header 
    kicker="Featured"
    title="Available Games"
    subtitle="Browser-based games ready to play now"
/>
```

**Props:**
- `kicker` (optional): Small uppercase label above title
- `title` (optional): Main heading (h2)
- `subtitle` (optional): Descriptive text below title

---

### Card (`card.blade.php`)
Interactive card component for links, games, or feature displays.

**Usage:**
```blade
<x-ui.card
    title="Sudoku"
    subtitle="Classic number puzzle game"
    href="{{ route('games.play', 'sudoku') }}"
    icon="puzzle-piece"
    meta="Play now"
/>
```

**Props:**
- `title` (required): Card heading
- `subtitle` (optional): Description text
- `href` (optional): Link destination (makes card clickable)
- `icon` (optional): Heroicon name (without `heroicon-o-` prefix)
- `meta` (optional): Small metadata text
- `disabled` (optional): Disables interaction (default: false)

**Features:**
- Uses `.glass` styling (translucent background with blur)
- Heroicons scale at 1.5rem (w-6 h-6)
- Hover states for interactive cards
- Chevron indicator for linked cards
- Proper focus-visible styles

---

### CTA Row (`cta-row.blade.php`)
Row of call-to-action buttons (primary and optional secondary).

**Usage:**
```blade
<x-ui.cta-row
    primaryHref="{{ route('games.index') }}"
    primaryLabel="Play Games"
    secondaryHref="{{ route('about') }}"
    secondaryLabel="Learn More"
/>
```

**Props:**
- `primaryHref` (required): Primary button link
- `primaryLabel` (required): Primary button text
- `secondaryHref` (optional): Secondary button link
- `secondaryLabel` (optional): Secondary button text

**Button Styles:**
- `.btn-primary`: Star yellow background (--star)
- `.btn-secondary`: Transparent with border
- Both have `:focus-visible` styles for accessibility

---

## Design Tokens

All components use CSS custom properties from the night sky design system:

```css
--ink: #f2f4f8           /* Primary text */
--ink-muted: #aeb6c2     /* Muted text */
--space-900: #050914     /* Deep space background */
--space-800: #0b1a33     /* Midnight blue */
--star: #f6e08f          /* Accent yellow */
--constellation: #9ec7ff /* Constellation blue */
--surface: rgba(255, 255, 255, 0.04)  /* Glass background */
--border: rgba(255, 255, 255, 0.10)   /* Subtle borders */
```

## Accessibility

All components follow WCAG AA standards:
- Semantic HTML elements
- Proper ARIA labels
- Focus-visible indicators (2px solid --star)
- Keyboard navigable
- Icons sized 1.25-1.5rem (w-5/w-6)
- Minimum contrast ratios met

## Icon Usage

Components use Heroicons (outline style):
```blade
<x-heroicon-o-puzzle-piece class="w-6 h-6" />
```

**Available sizes:**
- `w-5 h-5`: 1.25rem (inline with text)
- `w-6 h-6`: 1.5rem (cards, buttons)

**Do not** use sizes larger than 1.5rem in these components to maintain visual balance.

## Layout Components

### App Layout (`layouts/app.blade.php`)
Main application layout with sticky navigation.

**Usage:**
```blade
<x-layouts.app title="Page Title">
    <div class="section">
        <!-- Your content -->
    </div>
</x-layouts.app>
```

**Features:**
- Sticky top navigation with logo
- Dark theme with starfield background
- Centered content with max-width
- Footer with tagline

### Navigation Partial (`partials/nav.blade.php`)
Simple navigation links for the main nav.

**Links:**
- Home
- Games
- Blog
- About
- Contact

### Footer Partial (`partials/footer.blade.php`)
Elegant horizon footer with subtle gradient line and poetic tagline:
> "The sky is the limit."

---

## Examples

### Full Section Example
```blade
<section class="section space-y-12">
    <x-ui.section-header 
        kicker="Browse"
        title="Available Games"
        subtitle="Classic games reimagined for the web"
    />
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-ui.card
            title="Sudoku"
            subtitle="Number puzzle challenge"
            href="{{ route('games.play', 'sudoku') }}"
            icon="squares-2x2"
        />
        
        <x-ui.card
            title="Connect 4"
            subtitle="Strategic disc-dropping game"
            href="{{ route('games.play', 'connect4') }}"
            icon="circle-stack"
        />
    </div>
    
    <x-ui.cta-row
        primaryHref="{{ route('games.index') }}"
        primaryLabel="View All Games"
    />
</section>
```

---

## Notes

- All components are **prop-validated** with sensible defaults
- Components use **minimal markup** - no inline scripts/styles
- **Responsive** by default (mobile-first approach)
- **Dark theme** optimized with proper contrast
- Follow the **night sky aesthetic** consistently

