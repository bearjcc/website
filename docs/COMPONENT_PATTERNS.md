# Component Patterns

Technical implementation patterns for Ursa Minor's key UI components.

## Embla Carousel with Constellation Pagination

### Overview
A lightweight, performant carousel using Embla Carousel with custom "constellation pagination" — dots connected by faint lines to suggest star connections.

### Installation
```bash
npm install embla-carousel
```

### Basic Implementation

```blade
{{-- resources/views/components/ui/carousel.blade.php --}}
<div x-data="emblaCarousel()" x-init="init($el)" class="relative">
    <div data-embla-viewport class="overflow-hidden">
        <div class="flex gap-4 md:gap-6">
            {{ $slot }}
        </div>
    </div>

    {{-- Navigation buttons --}}
    <button type="button"
        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 grid place-items-center rounded-full
               bg-[hsl(var(--surface)/.14)] border border-[hsl(var(--border)/.12)] text-ink/80
               hover:bg-[hsl(var(--surface)/.2)] focus-visible:ring-2 focus-visible:ring-constellation"
        @click="embla.scrollPrev()" 
        aria-label="Previous">
        <x-heroicon-o-chevron-left class="w-5 h-5"/>
    </button>

    <button type="button"
        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 grid place-items-center rounded-full
               bg-[hsl(var(--surface)/.14)] border border-[hsl(var(--border)/.12)] text-ink/80
               hover:bg-[hsl(var(--surface)/.2)] focus-visible:ring-2 focus-visible:ring-constellation"
        @click="embla.scrollNext()" 
        aria-label="Next">
        <x-heroicon-o-chevron-right class="w-5 h-5"/>
    </button>

    {{-- Constellation pagination --}}
    <div class="mt-4 flex items-center justify-center gap-3" x-show="totalSlides > 1">
        <template x-for="(slide, index) in totalSlides" :key="index">
            <div class="flex items-center gap-3">
                <span 
                    class="w-1.5 h-1.5 rounded-full transition-all cursor-pointer"
                    :class="index === currentSlide ? 'bg-ink/80 scale-125' : 'bg-ink/40'"
                    @click="embla.scrollTo(index)">
                </span>
                <span 
                    x-show="index < totalSlides - 1"
                    class="w-6 h-px bg-ink/20">
                </span>
            </div>
        </template>
    </div>
</div>
```

### Alpine Component

```javascript
// resources/js/carousel.js
import EmblaCarousel from 'embla-carousel'

window.emblaCarousel = () => ({
    embla: null,
    currentSlide: 0,
    totalSlides: 0,
    
    init(el) {
        const viewport = el.querySelector('[data-embla-viewport]')
        this.embla = EmblaCarousel(viewport, {
            loop: false,
            align: 'start',
            dragFree: false,
            containScroll: 'trimSnaps'
        })
        
        this.totalSlides = this.embla.scrollSnapList().length
        
        this.embla.on('select', () => {
            this.currentSlide = this.embla.selectedScrollSnap()
        })
    }
})
```

### Usage

```blade
<x-ui.carousel>
    @foreach($games as $game)
        <x-ui.game-card :game="$game" />
    @endforeach
</x-ui.carousel>
```

### Design Notes
- Dots represent stars in a constellation
- Active dot is brighter and slightly larger
- Lines between dots suggest constellation connections
- Keep pagination subtle (low opacity)
- Respect `prefers-reduced-motion`

---

## Starfield Background

### Technical Specifications

**Purpose**: Page-height aware twinkling starfield that creates depth without distraction.

**Key Requirements**:
- Canvas sized to `document.documentElement.scrollHeight` (not viewport)
- Stars scale with page area: ~0.00015 stars per pixel² (120-350 stars typical)
- Gentle twinkle: opacity drift at 0.1-0.3Hz, amplitude ≤ 0.15 alpha
- Respects `prefers-reduced-motion` (static render when enabled)
- CPU-friendly: `requestAnimationFrame` or low-frequency interval
- No pointer events, no layout thrash

### Implementation

```javascript
// resources/js/starfield.js
class Starfield {
    constructor() {
        this.canvas = null
        this.ctx = null
        this.stars = []
        this.animationId = null
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
    }

    init() {
        this.createCanvas()
        this.generateStars()
        if (!this.reducedMotion) {
            this.animate()
        } else {
            this.drawStatic()
        }
        
        // Resize handler with debounce
        let resizeTimeout
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout)
            resizeTimeout = setTimeout(() => this.resize(), 150)
        })
    }

    createCanvas() {
        this.canvas = document.createElement('canvas')
        this.canvas.id = 'um-starfield'
        this.canvas.setAttribute('aria-hidden', 'true')
        this.canvas.style.cssText = `
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        `
        document.body.prepend(this.canvas)
        this.ctx = this.canvas.getContext('2d')
        this.resize()
    }

    resize() {
        this.canvas.width = window.innerWidth
        this.canvas.height = document.documentElement.scrollHeight
        this.generateStars()
        if (!this.reducedMotion) {
            this.drawStatic()
        }
    }

    generateStars() {
        const area = this.canvas.width * this.canvas.height
        const count = Math.max(120, Math.min(350, Math.floor(area * 0.00015)))
        
        this.stars = Array.from({ length: count }, () => ({
            x: Math.random() * this.canvas.width,
            y: Math.random() * this.canvas.height,
            radius: 0.4 + Math.random() * 0.8,
            baseAlpha: 0.2 + Math.random() * 0.4,
            speed: 0.001 + Math.random() * 0.002,
            phase: Math.random() * Math.PI * 2
        }))
    }

    animate(timestamp = 0) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
        
        this.stars.forEach(star => {
            const alpha = star.baseAlpha + Math.sin(timestamp * star.speed + star.phase) * 0.12
            this.ctx.fillStyle = `rgba(242, 244, 248, ${alpha})`
            this.ctx.beginPath()
            this.ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2)
            this.ctx.fill()
        })
        
        this.animationId = requestAnimationFrame((ts) => this.animate(ts))
    }

    drawStatic() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
        this.stars.forEach(star => {
            this.ctx.fillStyle = `rgba(242, 244, 248, ${star.baseAlpha})`
            this.ctx.beginPath()
            this.ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2)
            this.ctx.fill()
        })
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    new Starfield().init()
})
```

### Performance Considerations
- Use `clamp()` to limit star count (120 min, 350 max)
- Debounce resize events (150ms)
- Avoid reflow triggers in animation loop
- Canvas is fixed, not absolute, to avoid document height changes

---

## Horizon Footer

### Overview
The footer as "horizon line" — grounding the infinite sky with a subtle earth silhouette and sunset glow.

### Structure

```blade
{{-- resources/views/components/ui/horizon-footer.blade.php --}}
<footer class="relative mt-20 md:mt-24">
    {{-- Sunset line --}}
    <div class="um-horizon-line" aria-hidden="true"></div>

    {{-- Earth silhouette --}}
    <div class="um-horizon-silhouette" aria-hidden="true">
        {{-- Optional: SVG ridge/treeline --}}
    </div>

    {{-- Back to top button --}}
    <a href="#top" 
       class="um-top-btn absolute left-1/2 -translate-x-1/2 -top-5"
       aria-label="{{ __('ui.back_to_top') }}">
        <x-heroicon-o-star class="w-4 h-4" />
    </a>

    {{-- Footer content --}}
    <div class="section mt-6 text-center text-sm text-ink/70">
        <p>&copy; {{ now()->year }} Ursa Minor Games</p>
    </div>
</footer>
```

### Styles

```css
/* Sunset line */
.um-horizon-line {
    height: 1px;
    background: linear-gradient(90deg,
        hsl(var(--sunset) / 0) 0%,
        hsl(var(--sunset) / .45) 50%,
        hsl(var(--sunset) / 0) 100%);
}

/* Earth silhouette */
.um-horizon-silhouette {
    height: 36px;
    background: linear-gradient(to bottom, 
        hsl(var(--earth) / .96), 
        hsl(var(--earth-dark) / 1));
}

/* Back to top button */
.um-top-btn {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    border-radius: 9999px;
    background: hsl(var(--surface) / .08);
    border: 1px solid hsl(var(--border) / .12);
    color: hsl(var(--ink));
    backdrop-filter: blur(4px);
    transition: transform .15s ease, box-shadow .15s ease;
}

.um-top-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 0 0 6px hsl(var(--surface) / .04);
}

.um-top-btn:focus-visible {
    outline: 2px solid hsl(var(--constellation));
    outline-offset: 2px;
}

/* Optional subtle rays on hover */
@media (prefers-reduced-motion: no-preference) {
    .um-top-btn::after {
        content: "";
        position: absolute;
        inset: -6px;
        background: radial-gradient(closest-side, hsl(var(--ink) / .12), transparent 70%);
        opacity: 0;
        transition: opacity .2s ease;
        border-radius: 9999px;
        pointer-events: none;
    }
    .um-top-btn:hover::after {
        opacity: 1;
    }
}
```

### Design Notes
- Button sits **above** the horizon (on sky side), not straddling
- Sunset line is warm (28 100% 78% in HSL) but low opacity
- Earth colors are dark, desaturated blue-green (135 10% 12%)
- Generous spacing above footer (≥ 64px) to let content breathe
- Optional SVG silhouette can be added for trees/ridge

---

## Visual-First Game Cards

### Philosophy
Game cards prioritize visual recognition over text labels. Users identify games by their visual motif (board pattern, glyph) rather than reading a title.

### Implementation

```blade
{{-- resources/views/components/ui/game-card.blade.php --}}
@props([
    'href' => '#',
    'title' => '',
    'aria' => null,
    'motif' => 'sparkles'
])

@php
$label = $aria ?: ($title ? "Play {$title}" : 'Open game');
@endphp

<a href="{{ $href }}"
   class="um-game-card group relative block rounded-2xl border um-border bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-constellation"
   aria-label="{{ $label }}">
    
    {{-- Visual motif layer --}}
    <div class="um-motif absolute inset-0 grid place-items-center">
        @switch($motif)
            @case('tictactoe')
                {{-- 3x3 grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-ink/70">
                    <g stroke="currentColor" stroke-width="4" stroke-linecap="round">
                        <line x1="40" y1="10" x2="40" y2="110" />
                        <line x1="80" y1="10" x2="80" y2="110" />
                        <line x1="10" y1="40" x2="110" y2="40" />
                        <line x1="10" y1="80" x2="110" y2="80" />
                    </g>
                </svg>
                @break
            @case('chess')
                <x-heroicon-o-square-3-stack-3d class="w-16 h-16 text-ink/70" />
                @break
            @case('cards')
                <x-heroicon-o-rectangle-stack class="w-16 h-16 text-ink/70" />
                @break
            @default
                <x-heroicon-o-sparkles class="w-14 h-14 text-ink/70" />
        @endswitch
    </div>

    {{-- Title reveal on hover/focus --}}
    <h3 class="sr-only">{{ $title }}</h3>
    <div class="um-title pointer-events-none absolute left-4 right-4 bottom-4 translate-y-3 opacity-0
                group-hover:translate-y-0 group-hover:opacity-100
                group-focus:translate-y-0 group-focus:opacity-100
                transition-all duration-150 ease-out">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[hsl(var(--surface)/.24)] border um-border text-ink text-sm">
            <span>{{ $title }}</span>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-ink/80" />
        </div>
    </div>

    {{-- Aspect ratio box --}}
    <div class="pt-[70%] md:pt-[66%]"></div>
</a>
```

### Styles

```css
.um-game-card {
    transition: transform .15s ease, border-color .15s ease;
}

.um-game-card:hover {
    transform: translateY(-2px);
}

@media (prefers-reduced-motion: reduce) {
    .um-game-card {
        transition: none;
    }
    .um-game-card:hover {
        transform: none;
    }
}
```

### Motif Guidelines
- Each game type has a unique, recognizable visual motif
- Motifs should be simple line art or icons
- Size: 80-120px for board patterns, 48-64px for icons
- Opacity: 70-80% to remain subtle
- Color: Use `text-ink/70` for consistency

### Accessibility Requirements
- Full accessible text via `aria-label`
- Screen-reader-only `<h3>` with title
- Keyboard focus triggers same reveal as hover
- 44px minimum touch target (entire card exceeds this)
- Visible focus ring using constellation color

### Usage

```blade
<x-ui.game-card
    :href="route('games.play', 'tictactoe')"
    title="Tic-Tac-Toe"
    motif="tictactoe"
/>
```

---

## Constellation Lines as UI Connectors

### Concept
Instead of traditional dividers or borders, use faint lines that connect "stars" (UI elements) to suggest relationships — like constellations.

### Use Cases
- Between navigation items
- Connecting related cards in a grid
- Timeline/progress indicators
- Breadcrumb trails

### Simple Implementation

```html
{{-- Between two elements --}}
<div class="flex items-center gap-4">
    <button class="um-star-point">Home</button>
    <div class="h-px w-12 bg-ink/20"></div>
    <button class="um-star-point">Games</button>
</div>
```

### Animated Version

```css
.um-constellation-line {
    position: relative;
    height: 1px;
    background: transparent;
}

.um-constellation-line::after {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 0;
    height: 1px;
    background: hsl(var(--ink) / .2);
    transition: width 0.3s ease;
}

.um-constellation-line.revealed::after {
    width: 100%;
}
```

### Design Notes
- Keep lines subtle (low opacity)
- Animate reveal on scroll or hover (optional)
- Don't overuse — should feel intentional, not decorative
- Works best with sparse layouts

---

## Night Sky Motif in Games

### Loading States

Instead of spinners, show stars appearing:

```html
<div class="um-game-loading">
    <div class="um-star" style="animation-delay: 0s"></div>
    <div class="um-star" style="animation-delay: 0.2s"></div>
    <div class="um-star" style="animation-delay: 0.4s"></div>
</div>
```

```css
.um-star {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: hsl(var(--star));
    animation: starPulse 1.2s ease-in-out infinite;
}

@keyframes starPulse {
    0%, 100% { opacity: 0.2; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.2); }
}
```

### Score/Progress Indicators

Use constellation formation to show progress:

```html
<div class="um-constellation-progress">
    @for($i = 1; $i <= 5; $i++)
        <div class="um-progress-star {{ $currentLevel >= $i ? 'lit' : '' }}"></div>
        @if($i < 5)
            <div class="um-progress-line {{ $currentLevel > $i ? 'lit' : '' }}"></div>
        @endif
    @endfor
</div>
```

### Game Backgrounds

Subtle starfield specific to game canvas:

```css
.game-board {
    background: 
        radial-gradient(1px 1px at 20% 30%, hsl(var(--ink) / .08), transparent),
        radial-gradient(1px 1px at 80% 70%, hsl(var(--ink) / .06), transparent),
        hsl(var(--space-900));
}
```

---

## Questions?

When implementing these patterns:
- Keep animations subtle and respect reduced-motion
- Maintain accessibility (ARIA, keyboard nav, screen readers)
- Use HSL color tokens for consistency
- Test on actual devices, not just DevTools

