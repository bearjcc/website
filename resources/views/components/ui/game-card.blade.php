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
   class="um-game-card group relative block rounded-2xl glass overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)] transition-all duration-150 ease-out hover:border-[color:var(--ink)]/20 motion-safe:hover:-translate-y-[2px]"
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
                {{-- Connect 4 grid with some filled pieces --}}
                <svg width="120" height="100" viewBox="0 0 120 100" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2">
                        @for($row = 0; $row < 6; $row++)
                            @for($col = 0; $col < 7; $col++)
                                @php
                                    // Fill some bottom pieces to show gameplay
                                    $filled = ($row === 5 && in_array($col, [1, 3, 4])) 
                                           || ($row === 4 && in_array($col, [1, 3]))
                                           || ($row === 3 && $col === 3);
                                @endphp
                                <circle 
                                    cx="{{ 10 + $col * 16 }}" 
                                    cy="{{ 10 + $row * 16 }}" 
                                    r="6" 
                                    fill="{{ $filled ? 'currentColor' : 'none' }}"
                                    opacity="{{ $filled ? '0.4' : '1' }}"
                                />
                            @endfor
                        @endfor
                    </g>
                </svg>
                @break
            
            @case('sudoku')
                {{-- Sudoku grid with some prefilled numbers --}}
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
                    {{-- Some prefilled numbers --}}
                    <g fill="currentColor" font-size="12" font-weight="600" text-anchor="middle" opacity="0.5">
                        <text x="20" y="25">5</text>
                        <text x="60" y="25">3</text>
                        <text x="100" y="25">7</text>
                        <text x="40" y="45">6</text>
                        <text x="80" y="65">9</text>
                        <text x="20" y="85">4</text>
                        <text x="100" y="105">2</text>
                    </g>
                </svg>
                @break
            
            @case('chess')
                {{-- Simple 2x2 checkerboard with rook icon --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    {{-- 2x2 Checkerboard pattern --}}
                    <rect x="30" y="30" width="30" height="30" fill="currentColor" opacity="0.2" />
                    <rect x="60" y="60" width="30" height="30" fill="currentColor" opacity="0.2" />
                    <rect x="30" y="30" width="60" height="60" stroke="currentColor" stroke-width="2" fill="none" />
                    {{-- Rook silhouette in center --}}
                    <g transform="translate(60, 60)">
                        <rect x="-10" y="-15" width="20" height="25" fill="currentColor" opacity="0.6" />
                        <rect x="-12" y="-18" width="4" height="3" fill="currentColor" opacity="0.6" />
                        <rect x="-4" y="-18" width="4" height="3" fill="currentColor" opacity="0.6" />
                        <rect x="4" y="-18" width="4" height="3" fill="currentColor" opacity="0.6" />
                        <rect x="-12" y="10" width="24" height="3" fill="currentColor" opacity="0.6" />
                    </g>
                </svg>
                @break
            
            @case('checkers')
                {{-- Checkers 4x4 board with sample pieces --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2.5">
                        <rect x="10" y="10" width="100" height="100" fill="none" />
                        {{-- 4x4 grid --}}
                        <line x1="35" y1="10" x2="35" y2="110" />
                        <line x1="60" y1="10" x2="60" y2="110" />
                        <line x1="85" y1="10" x2="85" y2="110" />
                        <line x1="10" y1="35" x2="110" y2="35" />
                        <line x1="10" y1="60" x2="110" y2="60" />
                        <line x1="10" y1="85" x2="110" y2="85" />
                        {{-- Sample pieces --}}
                        <circle cx="22.5" cy="22.5" r="8" fill="currentColor" opacity="0.4" />
                        <circle cx="72.5" cy="22.5" r="8" fill="none" />
                        <circle cx="97.5" cy="97.5" r="8" fill="currentColor" opacity="0.4" />
                    </g>
                </svg>
                @break
            
            @case('minesweeper')
                {{-- Grid with numbers showing nearby mines --}}
                <svg width="120" height="120" viewBox="0 0 120 120" class="opacity-80 text-[color:var(--ink)]/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2">
                        {{-- 4x4 grid --}}
                        <rect x="20" y="20" width="80" height="80" fill="none" />
                        <line x1="40" y1="20" x2="40" y2="100" />
                        <line x1="60" y1="20" x2="60" y2="100" />
                        <line x1="80" y1="20" x2="80" y2="100" />
                        <line x1="20" y1="40" x2="100" y2="40" />
                        <line x1="20" y1="60" x2="100" y2="60" />
                        <line x1="20" y1="80" x2="100" y2="80" />
                    </g>
                    {{-- Numbers showing mine proximity --}}
                    <g fill="currentColor" font-size="14" font-weight="700" text-anchor="middle">
                        <text x="30" y="35" opacity="0.5">1</text>
                        <text x="50" y="35" opacity="0.5">2</text>
                        <text x="30" y="55" opacity="0.5">3</text>
                        <text x="90" y="75" opacity="0.5">1</text>
                    </g>
                    {{-- Small mine in one square --}}
                    <g transform="translate(70, 50)" opacity="0.4">
                        <circle cx="0" cy="0" r="6" fill="currentColor" />
                        <line x1="-8" y1="0" x2="-7" y2="0" stroke="currentColor" stroke-width="1.5" />
                        <line x1="7" y1="0" x2="8" y2="0" stroke="currentColor" stroke-width="1.5" />
                        <line x1="0" y1="-8" x2="0" y2="-7" stroke="currentColor" stroke-width="1.5" />
                        <line x1="0" y1="7" x2="0" y2="8" stroke="currentColor" stroke-width="1.5" />
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
                {{-- 2048 tiles with some numbers --}}
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
                    {{-- Numbers in some tiles --}}
                    <g fill="currentColor" font-size="11" font-weight="700" text-anchor="middle">
                        <text x="22.5" y="27" opacity="0.5">2</text>
                        <text x="52.5" y="27" opacity="0.5">4</text>
                        <text x="82.5" y="57" opacity="0.5">8</text>
                        <text x="22.5" y="87" opacity="0.5">16</text>
                    </g>
                </svg>
                @break
            
            @default
                {{-- Default sparkle icon --}}
                <x-heroicon-o-sparkles class="w-14 h-14 text-[color:var(--ink)]/70" aria-hidden="true" />
        @endswitch
    </div>

    {{-- Title reveal on hover/focus - card grows taller, name fades in --}}
    <h3 class="sr-only">{{ $title }}</h3>
    <div class="um-title-reveal absolute inset-x-0 bottom-0 flex items-end justify-center
                opacity-0 group-hover:opacity-100 group-focus:opacity-100
                transition-opacity duration-200 ease-out pointer-events-none">
        <div class="w-full text-center px-4 py-3 bg-gradient-to-t from-[hsl(var(--surface)/.95)] to-transparent backdrop-blur-sm">
            <span class="text-[color:var(--ink)] text-sm font-medium">{{ $title }}</span>
        </div>
    </div>
</a>

