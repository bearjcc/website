@props([
    'title' => '',
    'status' => null,  // 'won', 'lost', 'playing', 'draw'
    'showReset' => true,
    'showHint' => false,
    'showUndo' => false,
])

<div class="min-h-screen flex flex-col">
    {{-- Game Header --}}
    <header class="section py-6">
        <div class="flex items-center justify-between">
            {{-- Title & back --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('games.index') }}" 
                   class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
                   aria-label="{{ __('ui.back_to_games') }}">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
                </a>
                
                @if($title)
                    <h1 class="text-xl md:text-2xl font-bold text-ink">{{ $title }}</h1>
                @endif
            </div>

            {{-- Game controls --}}
            <div class="flex items-center gap-2">
                @if($showHint)
                    {{ $hint ?? '' }}
                @endif

                @if($showUndo)
                    {{ $undo ?? '' }}
                @endif

                @if($showReset)
                    {{ $reset ?? '' }}
                @endif
            </div>
        </div>

        {{-- Status message (win/loss/draw) --}}
        @if($status)
            <div class="mt-4 px-4 py-3 rounded-lg {{ $this->getStatusClass($status) }}">
                <p class="text-sm font-medium text-center">
                    {{ $this->getStatusMessage($status) }}
                </p>
            </div>
        @endif
    </header>

    {{-- Game Area --}}
    <main class="section flex-1 flex flex-col items-center justify-center pb-20">
        <div class="w-full max-w-2xl">
            {{ $slot }}
        </div>
    </main>
</div>

@once
    @push('styles')
    <style>
        /* Game wrapper utilities */
        .game-status-won {
            background: hsl(var(--star) / .12);
            border: 1px solid hsl(var(--star) / .2);
            color: hsl(var(--star));
        }
        
        .game-status-lost {
            background: hsl(0 60% 60% / .12);
            border: 1px solid hsl(0 60% 60% / .2);
            color: hsl(0 60% 80%);
        }
        
        .game-status-draw {
            background: hsl(var(--constellation) / .12);
            border: 1px solid hsl(var(--constellation) / .2);
            color: hsl(var(--constellation));
        }
        
        .game-status-playing {
            background: hsl(var(--surface) / .08);
            border: 1px solid hsl(var(--border) / .12);
            color: hsl(var(--ink));
        }
    </style>
    @endpush
@endonce

@php
    // Helper methods (could be moved to a trait)
    function getStatusClass($status) {
        return match($status) {
            'won' => 'game-status-won',
            'lost' => 'game-status-lost',
            'draw' => 'game-status-draw',
            'playing' => 'game-status-playing',
            default => 'game-status-playing',
        };
    }

    function getStatusMessage($status) {
        return match($status) {
            'won' => 'You won!',
            'lost' => 'Game over.',
            'draw' => 'Draw.',
            'playing' => 'Your turn.',
            default => '',
        };
    }
@endphp

