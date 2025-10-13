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
   class="um-game-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-constellation transition-all duration-150 ease-out hover:-translate-y-1"
   aria-label="{{ $label }}">
    
    {{-- Visual motif layer --}}
    <div class="um-motif absolute inset-0 grid place-items-center">
        @switch($motif)
            @case('tictactoe')
                {{-- 3x3 grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="3" stroke-linecap="round">
                        <line x1="40" y1="10" x2="40" y2="110" />
                        <line x1="80" y1="10" x2="80" y2="110" />
                        <line x1="10" y1="40" x2="110" y2="40" />
                        <line x1="10" y1="80" x2="110" y2="80" />
                    </g>
                </svg>
                @break

            @case('chess')
                {{-- Chess knight --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-70 text-ink/70" aria-hidden="true">
                    <path d="M50 20 L35 35 L40 50 L30 70 L70 70 L60 50 L65 35 Z" 
                          fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                    <circle cx="55" cy="30" r="4" fill="currentColor"/>
                </svg>
                @break

            @case('checkers')
                {{-- Checkerboard pattern --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <rect x="10" y="10" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="60" y="10" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="35" y="35" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="85" y="35" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="10" y="60" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="60" y="60" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="35" y="85" width="25" height="25" fill="currentColor" opacity="0.6"/>
                    <rect x="85" y="85" width="25" height="25" fill="currentColor" opacity="0.6"/>
                </svg>
                @break

            @case('connect4')
                {{-- Connect 4 grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    @for($row = 0; $row < 4; $row++)
                        @for($col = 0; $col < 4; $col++)
                            <circle cx="{{ 20 + $col * 26 }}" cy="{{ 20 + $row * 26 }}" r="10" 
                                    fill="none" stroke="currentColor" stroke-width="2"/>
                        @endfor
                    @endfor
                </svg>
                @break

            @case('puzzle')
            @case('sudoku')
                {{-- Sudoku grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-linecap="round">
                        {{-- Thick lines for 3x3 boxes --}}
                        <g stroke-width="3">
                            <line x1="10" y1="10" x2="10" y2="110" />
                            <line x1="110" y1="10" x2="110" y2="110" />
                            <line x1="10" y1="10" x2="110" y2="10" />
                            <line x1="10" y1="110" x2="110" y2="110" />
                            <line x1="43" y1="10" x2="43" y2="110" />
                            <line x1="77" y1="10" x2="77" y2="110" />
                            <line x1="10" y1="43" x2="110" y2="43" />
                            <line x1="10" y1="77" x2="110" y2="77" />
                        </g>
                        {{-- Thin lines for cells --}}
                        <g stroke-width="1" opacity="0.5">
                            <line x1="27" y1="10" x2="27" y2="110" />
                            <line x1="60" y1="10" x2="60" y2="110" />
                            <line x1="93" y1="10" x2="93" y2="110" />
                            <line x1="10" y1="27" x2="110" y2="27" />
                            <line x1="10" y1="60" x2="110" y2="60" />
                            <line x1="10" y1="93" x2="110" y2="93" />
                        </g>
                    </g>
                </svg>
                @break

            @case('minesweeper')
                {{-- Minesweeper grid with flag --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        @for($i = 0; $i < 5; $i++)
                            <line x1="10" y1="{{ 10 + $i * 25 }}" x2="110" y2="{{ 10 + $i * 25 }}" />
                            <line x1="{{ 10 + $i * 25 }}" y1="10" x2="{{ 10 + $i * 25 }}" y2="110" />
                        @endfor
                    </g>
                    <g fill="currentColor">
                        <polygon points="70,45 70,30 85,37.5" />
                        <rect x="68" y="45" width="2" height="20"/>
                    </g>
                </svg>
                @break

            @case('snake')
                {{-- Snake path --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <path d="M 20 60 Q 40 40, 60 60 T 100 60" 
                          fill="none" stroke="currentColor" stroke-width="8" 
                          stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="100" cy="60" r="6" fill="currentColor"/>
                </svg>
                @break

            @case('cards')
            @case('solitaire')
                {{-- Playing cards stack --}}
                <svg width="100" height="120" viewBox="0 0 100 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        <rect x="15" y="35" width="45" height="65" rx="4"/>
                        <rect x="25" y="25" width="45" height="65" rx="4"/>
                        <rect x="35" y="15" width="45" height="65" rx="4"/>
                    </g>
                </svg>
                @break

            @case('memory')
                {{-- Memory card pairs --}}
                <svg width="120" height="100" viewBox="0 0 120 100" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        <rect x="10" y="20" width="25" height="35" rx="3"/>
                        <rect x="45" y="20" width="25" height="35" rx="3"/>
                        <rect x="80" y="20" width="25" height="35" rx="3"/>
                    </g>
                    <g fill="currentColor" opacity="0.6">
                        <circle cx="22.5" cy="37.5" r="3"/>
                        <circle cx="92.5" cy="37.5" r="3"/>
                    </g>
                </svg>
                @break

            @case('2048')
            @case('twenty-forty-eight')
                {{-- 2048 grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        @for($i = 0; $i < 4; $i++)
                            @for($j = 0; $j < 4; $j++)
                                <rect x="{{ 10 + $j * 27 }}" y="{{ 10 + $i * 27 }}" 
                                      width="24" height="24" rx="3"/>
                            @endfor
                        @endfor
                    </g>
                </svg>
                @break

            @case('board')
                {{-- Generic board game --}}
                <x-heroicon-o-rectangle-group class="w-16 h-16 text-ink/70" />
                @break

            @default
                {{-- Fallback sparkles --}}
                <x-heroicon-o-sparkles class="w-14 h-14 text-ink/70" />
        @endswitch
    </div>

    {{-- Title reveal on hover/focus; sr-only by default for a11y --}}
    <h3 class="sr-only">{{ $title }}</h3>
    <div class="um-title pointer-events-none absolute left-4 right-4 bottom-4 translate-y-3 opacity-0
                group-hover:translate-y-0 group-hover:opacity-100
                group-focus:translate-y-0 group-focus:opacity-100
                transition-all duration-150 ease-out">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[hsl(var(--surface)/.24)] 
                    border border-[hsl(var(--border)/.12)] backdrop-blur">
            <span class="text-ink text-sm font-medium">{{ $title }}</span>
            <x-heroicon-o-chevron-right class="w-4 h-4 text-ink/80" />
        </div>
    </div>

    {{-- Size box to enforce aspect ratio --}}
    <div class="pt-[70%] md:pt-[66%]"></div>
</a>

@once
    @push('styles')
    <style>
        /* Card hover state (subtle lift) */
        .um-game-card {
            transition: transform .15s ease, border-color .15s ease;
        }
        
        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .um-game-card,
            .um-game-card .um-title {
                transition: none;
            }
            .um-game-card:hover {
                transform: none;
            }
        }
    </style>
    @endpush
@endonce
