# Flux UI Integration Guide

Guide for using Flux UI components with the Ursa Minor night sky theme.

## Overview

**Flux UI** is a premium component library for Livewire applications. We've integrated it to provide consistent, accessible UI components while maintaining our night sky aesthetic.

## Installation

Flux UI is already installed and configured:

```bash
# Already installed via Composer
composer require livewire/flux
```

**Configuration**:
- ✅ Tailwind config includes Flux paths
- ✅ Layout includes `@fluxStyles` and `@fluxScripts`
- ✅ Night sky theme CSS variables available

## Night Sky Theme Integration

### Our Custom Theme Components

We've created Flux-compatible components that match our night sky theme:

#### 1. Flux Button (`<x-ui.flux-button>`)

Buttons styled with night sky colors:

```blade
{{-- Primary button (star yellow on dark) --}}
<x-ui.flux-button variant="primary" href="{{ route('games.index') }}">
    Play Now
</x-ui.flux-button>

{{-- Secondary button (transparent with border) --}}
<x-ui.flux-button variant="secondary" href="{{ route('about') }}">
    Learn More
</x-ui.flux-button>

{{-- Ghost button (subtle hover) --}}
<x-ui.flux-button variant="ghost">
    Cancel
</x-ui.flux-button>

{{-- With icon --}}
<x-ui.flux-button variant="primary" icon="play" iconPosition="left">
    Start Game
</x-ui.flux-button>
```

**Props**:
- `variant`: `primary`, `secondary`, `ghost`
- `href`: Optional link URL (renders as `<a>`)
- `icon`: Heroicon name (without `heroicon-o-` prefix)
- `iconPosition`: `left` or `right`

#### 2. Flux Card (`<x-ui.flux-card>`)

Cards with glass morphism effect:

```blade
{{-- Basic card with heading --}}
<x-ui.flux-card heading="Game Title">
    <p>Game description goes here.</p>
    <p class="text-sm">More details...</p>
</x-ui.flux-card>

{{-- Interactive card with link --}}
<x-ui.flux-card 
    heading="Browser Games" 
    icon="puzzle-piece"
    href="{{ route('games.index') }}"
>
    <p>Play games directly in your browser.</p>
</x-ui.flux-card>

{{-- Card with custom content --}}
<x-ui.flux-card>
    <h3 class="text-xl font-bold text-ink mb-2">Custom Content</h3>
    <p>Any content you want...</p>
</x-ui.flux-card>
```

**Props**:
- `heading`: Optional card title
- `href`: Optional link (makes entire card clickable)
- `icon`: Optional Heroicon name
- `interactive`: Set to `true` for hover effects without href

#### 3. Flux Input (`<x-ui.flux-input>`)

Form inputs with night sky styling:

```blade
{{-- Basic input --}}
<x-ui.flux-input 
    label="Game Name"
    placeholder="Enter game name..."
    name="game_name"
/>

{{-- Required input with hint --}}
<x-ui.flux-input 
    label="Email Address"
    type="email"
    name="email"
    placeholder="you@example.com"
    hint="We'll never share your email."
    required
/>

{{-- Input with error --}}
<x-ui.flux-input 
    label="Username"
    name="username"
    error="Username is already taken"
/>
```

**Props**:
- `label`: Input label text
- `type`: Input type (`text`, `email`, `password`, etc.)
- `hint`: Help text shown below input
- `error`: Error message (replaces hint)
- `required`: Shows asterisk indicator

## Night Sky Color Palette

Use these Tailwind classes with Flux components:

### Text Colors
```blade
text-ink           {{-- Light text (#f2f4f8) --}}
text-ink-muted     {{-- Muted text (#aeb6c2) --}}
text-star          {{-- Star yellow (#f6e08f) --}}
text-constellation {{-- Constellation blue (#9ec7ff) --}}
```

### Background Colors
```blade
bg-space-900       {{-- Deepest black (#050914) --}}
bg-space-800       {{-- Dark blue (#0b1a33) --}}
bg-space-700       {{-- Mid-dark (#0a1427) --}}
bg-space-600       {{-- Mid-light (#0e203d) --}}
bg-space-500       {{-- Lightest blue (#122a50) --}}
bg-star            {{-- Star yellow --}}
bg-constellation   {{-- Constellation blue --}}
```

### Effects
```blade
glass              {{-- Glass morphism effect --}}
backdrop-blur-md   {{-- Blur background --}}
border-border/10   {{-- Subtle border --}}
```

## Usage Examples

### Hero Section with Flux Buttons

```blade
<section class="py-24 text-center">
    <div class="section">
        <h1 class="h1 mb-6">Welcome to Ursa Minor</h1>
        <p class="p text-ink-muted mb-8">Building games under the stars</p>
        
        <div class="flex flex-wrap gap-3 justify-center">
            <x-ui.flux-button variant="primary" href="{{ route('games.index') }}" icon="play">
                Play Games
            </x-ui.flux-button>
            <x-ui.flux-button variant="secondary" href="{{ route('about') }}">
                Learn More
            </x-ui.flux-button>
        </div>
    </div>
</section>
```

### Game Grid with Flux Cards

```blade
<section class="py-16">
    <div class="section">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($games as $game)
                <x-ui.flux-card 
                    :heading="$game->name"
                    :href="route('games.show', $game)"
                    icon="puzzle-piece"
                >
                    <p class="text-sm">{{ $game->description }}</p>
                    <p class="text-xs text-ink-muted/70 mt-2">
                        {{ $game->players }} players
                    </p>
                </x-ui.flux-card>
            @endforeach
        </div>
    </div>
</section>
```

### Contact Form with Flux Inputs

```blade
<form wire:submit="submit" class="space-y-6">
    <x-ui.flux-input 
        label="Name"
        name="name"
        placeholder="Your name"
        wire:model="name"
        required
    />
    
    <x-ui.flux-input 
        label="Email"
        type="email"
        name="email"
        placeholder="you@example.com"
        wire:model="email"
        hint="We'll respond within 24 hours"
        required
    />
    
    <x-ui.flux-button variant="primary" type="submit">
        Send Message
    </x-ui.flux-button>
</form>
```

## Styling Guidelines

### DO ✅

```blade
{{-- Use night sky colors --}}
<x-ui.flux-button variant="primary" class="text-space-900 bg-star">
    Correctly Themed
</x-ui.flux-button>

{{-- Apply glass effects --}}
<x-ui.flux-card class="glass backdrop-blur-md">
    Card with glass effect
</x-ui.flux-card>

{{-- Use semantic spacing --}}
<div class="space-y-6">
    <x-ui.flux-card>Card 1</x-ui.flux-card>
    <x-ui.flux-card>Card 2</x-ui.flux-card>
</div>
```

### DON'T ❌

```blade
{{-- Don't use off-brand colors --}}
<x-ui.flux-button class="bg-purple-500">
    Wrong Color
</x-ui.flux-button>

{{-- Don't break night sky theme --}}
<x-ui.flux-card class="bg-white text-black">
    Breaks dark theme
</x-ui.flux-card>

{{-- Don't ignore accessibility --}}
<x-ui.flux-button aria-label="Not descriptive">
    Icon Only
</x-ui.flux-button>
```

## Accessibility

All Flux components maintain accessibility:

- ✅ Proper ARIA labels
- ✅ Keyboard navigation
- ✅ Focus visible states
- ✅ Minimum 44px touch targets
- ✅ WCAG AA contrast ratios

**Focus States**:
```blade
{{-- Focus uses star or constellation colors --}}
focus-visible:outline-2
focus-visible:outline-star
focus-visible:outline-offset-2
```

## Performance

Flux UI is optimized for performance:

- Tree-shakable components
- No unused CSS shipped
- Lazy-loaded JavaScript
- Tailwind JIT compilation

## When to Use Flux vs Custom Components

**Use Flux Components When**:
- Building forms and inputs
- Need consistent styling quickly
- Accessibility is critical
- Complex interactive patterns

**Use Custom Components When**:
- Unique one-off designs
- Brand-specific layouts
- Simple static content
- Performance is ultra-critical

## Migration Strategy

Gradually adopt Flux components:

1. **Phase 1**: Forms and inputs (in progress)
2. **Phase 2**: Cards and containers
3. **Phase 3**: Navigation and modals
4. **Phase 4**: Complex data tables

Keep existing components until Flux versions are tested and proven.

## Testing

Test Flux components like any other:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class FluxComponentTest extends TestCase
{
    public function test_flux_button_renders(): void
    {
        $view = $this->blade(
            '<x-ui.flux-button variant="primary">Click Me</x-ui.flux-button>'
        );
        
        $view->assertSee('Click Me');
        $view->assertSee('bg-star'); // Has primary styling
    }
    
    public function test_flux_card_with_link_is_clickable(): void
    {
        $view = $this->blade(
            '<x-ui.flux-card heading="Test" href="/test">Content</x-ui.flux-card>',
            ['heading' => 'Test Card', 'href' => '/test']
        );
        
        $view->assertSee('Test Card');
        $view->assertSee('href="/test"', false);
    }
}
```

## Resources

- **Flux Documentation**: https://fluxui.dev (check if you have access)
- **Night Sky Theme**: See `resources/css/app.css`
- **Component Source**: `resources/views/components/ui/flux-*.blade.php`
- **Tech Stack Guide**: `.cursor/rules/003-tech-stack.mdc`

## Troubleshooting

### Styles Not Applying

```bash
# Rebuild Tailwind
npm run build

# Or restart dev server
npm run dev
```

### Components Not Found

```bash
# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear
```

### Dark Theme Issues

Ensure you're using HSL color variables:

```css
/* Correct */
color: hsl(var(--ink));

/* Incorrect */
color: #f2f4f8;
```

---

**Built under the stars** | **Ursa Minor Games**

