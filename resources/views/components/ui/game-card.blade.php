@props([
    'href' => '#',
    'title' => '',
    'aria' => null,
    'motif' => 'sparkles', // 'tictactoe' | 'chess' | 'checkers' | 'puzzle' | 'cards' | 'board' | 'snake' | 'memory'
])

@php
    $label = $aria ?: ($title ? "Play {$title}" : 'Open game');
@endphp

<a href="{{ $href }}"
   class="um-game-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-constellation transition-all duration-150 ease-out interactive-glow smooth-transition mobile-enhanced"
   aria-label="{{ $label }}">

    {{-- Visual motif layer (shared with <x-ui.game-motif />) --}}
    <div class="um-motif absolute inset-0 grid place-items-center p-4 md:p-6">
        <x-ui.game-motif
            :motif="$motif"
            class="w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20 text-ink/70 opacity-80" />
    </div>

    {{-- Title reveal on hover/focus; sr-only by default for a11y --}}
    <h3 class="sr-only">{{ $title }}</h3>
    <div class="um-title pointer-events-none absolute left-4 right-4 top-6 -translate-y-3 opacity-0
                group-hover:translate-y-0 group-hover:opacity-100
                group-focus:translate-y-0 group-focus:opacity-100
                transition-all duration-150 ease-out">
        <div class="text-center">
            <span class="text-ink text-sm font-medium">{{ $title }}</span>
        </div>
    </div>

    {{-- Size box to enforce aspect ratio --}}
    <div class="pt-[100%]"></div>
</a>

@once
    @push('styles')
    <style>
        /* Card hover state (no size change) */
        .um-game-card {
            transition: border-color .15s ease, transform .2s ease, box-shadow .2s ease;
        }

        .um-game-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px hsl(var(--space-900) / 0.15);
        }

        /* Mobile touch feedback */
        @media (max-width: 768px) {
            .um-game-card:active {
                transform: scale(0.98);
                transition: transform 0.1s ease;
            }
        }

        /* Enhanced focus states */
        .um-game-card:focus-visible {
            outline: 2px solid hsl(var(--star));
            outline-offset: 2px;
            box-shadow: 0 0 0 4px hsl(var(--star) / 0.2);
        }

        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .um-game-card,
            .um-game-card .um-title {
                transition: none;
            }

            .um-game-card:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>
    @endpush
@endonce
