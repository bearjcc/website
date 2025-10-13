@props([
    'href' => '#',
    'title' => '',
    'aria' => null,
    'motif' => null,
])

@php
    $label = $aria ?: ($title ? "Play {$title}" : 'Open game');
@endphp

<a href="{{ $href }}"
   class="um-game-card group relative block rounded-2xl border border-[color:var(--ink)]/10 bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] transition-all duration-150 ease-out hover:border-[color:var(--ink)]/20 motion-safe:hover:-translate-y-[2px]"
   style="min-height: 44px;"
   aria-label="{{ $label }}">
    
    {{-- Visual motif layer --}}
    <div class="um-motif absolute inset-0 grid place-items-center pointer-events-none">
        @switch($motif)
            @case('tictactoe')
                {{-- 3x3 grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="3" stroke-linecap="round">
                        <line x1="40" y1="10" x2="40" y2="110" />
                        <line x1="80" y1="10" x2="80" y2="110" />
                        <line x1="10" y1="40" x2="110" y2="40" />
                        <line x1="10" y1="80" x2="110" y2="80" />
                    </g>
                </svg>
                @break
            
            @case('connect4')
                {{-- Connect 4 grid --}}
                <svg width="120" height="100" viewBox="0 0 120 100" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        @for($row = 0; $row < 6; $row++)
                            @for($col = 0; $col < 7; $col++)
                                <circle cx="{{ 10 + $col * 16 }}" cy="{{ 10 + $row * 16 }}" r="6" />
                            @endfor
                        @endfor
                    </g>
                </svg>
                @break
            
            @case('sudoku')
                {{-- Sudoku grid --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-linecap="round">
                        {{-- Thin lines --}}
                        <g stroke-width="1.5">
                            <line x1="30" y1="10" x2="30" y2="110" />
                            <line x1="50" y1="10" x2="50" y2="110" />
                            <line x1="70" y1="10" x2="70" y2="110" />
                            <line x1="90" y1="10" x2="90" y2="110" />
                            <line x1="10" y1="30" x2="110" y2="30" />
                            <line x1="10" y1="50" x2="110" y2="50" />
                            <line x1="10" y1="70" x2="110" y2="70" />
                            <line x1="10" y1="90" x2="110" y2="90" />
                        </g>
                        {{-- Thick lines --}}
                        <g stroke-width="3">
                            <line x1="10" y1="10" x2="110" y2="10" />
                            <line x1="10" y1="110" x2="110" y2="110" />
                            <line x1="10" y1="10" x2="10" y2="110" />
                            <line x1="110" y1="10" x2="110" y2="110" />
                            <line x1="10" y1="43.33" x2="110" y2="43.33" />
                            <line x1="10" y1="76.67" x2="110" y2="76.67" />
                            <line x1="43.33" y1="10" x2="43.33" y2="110" />
                            <line x1="76.67" y1="10" x2="76.67" y2="110" />
                        </g>
                    </g>
                </svg>
                @break
            
            @case('minesweeper')
                {{-- Mine glyph --}}
                <svg width="80" height="80" viewBox="0 0 80 80" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <circle cx="40" cy="40" r="12" fill="none" />
                        <line x1="40" y1="16" x2="40" y2="24" />
                        <line x1="40" y1="56" x2="40" y2="64" />
                        <line x1="16" y1="40" x2="24" y2="40" />
                        <line x1="56" y1="40" x2="64" y2="40" />
                        <line x1="23" y1="23" x2="29" y2="29" />
                        <line x1="51" y1="51" x2="57" y2="57" />
                        <line x1="57" y1="23" x2="51" y2="29" />
                        <line x1="29" y1="51" x2="23" y2="57" />
                    </g>
                </svg>
                @break
            
            @case('snake')
                {{-- Snake path --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <path d="M 20 50 L 40 50 L 40 30 L 60 30 L 60 70 L 80 70" 
                          stroke="currentColor" 
                          stroke-width="8" 
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          fill="none" />
                    <circle cx="80" cy="70" r="4" fill="currentColor" />
                </svg>
                @break
            
            @case('2048')
                {{-- 2048 tiles --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        <rect x="10" y="10" width="25" height="25" rx="3" />
                        <rect x="40" y="10" width="25" height="25" rx="3" />
                        <rect x="70" y="10" width="25" height="25" rx="3" />
                        <rect x="10" y="40" width="25" height="25" rx="3" />
                        <rect x="40" y="40" width="25" height="25" rx="3" />
                        <rect x="70" y="40" width="25" height="25" rx="3" />
                        <rect x="10" y="70" width="25" height="25" rx="3" />
                        <rect x="40" y="70" width="25" height="25" rx="3" />
                        <rect x="70" y="70" width="25" height="25" rx="3" />
                    </g>
                </svg>
                @break
            
            @default
                {{-- Default sparkle icon --}}
                <x-heroicon-o-sparkles class="w-14 h-14 text-[color:var(--ink)]/70" aria-hidden="true" />
        @endswitch
    </div>

    {{-- Title reveal on hover/focus - grows upward from bottom --}}
    <h3 class="sr-only">{{ $title }}</h3>
    <div class="um-title pointer-events-none absolute inset-x-0 bottom-0 h-0 overflow-hidden
                group-hover:h-12 group-focus:h-12
                transition-all duration-200 ease-out">
        <div class="absolute bottom-0 left-0 right-0 h-12 flex items-center justify-center
                    bg-[hsl(var(--surface)/.90)] border-t border-[color:var(--ink)]/10 
                    text-[color:var(--ink)] text-sm font-medium backdrop-blur-sm">
            <span>{{ $title }}</span>
        </div>
    </div>
</a>

