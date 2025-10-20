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
   class="um-game-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-constellation transition-all duration-150 ease-out"
   aria-label="{{ $label }}">
    
    {{-- Visual motif layer --}}
    <div class="um-motif absolute inset-0 grid place-items-center">
        @switch($motif)
            @case('tictactoe')
                {{-- 3x3 grid with Bethlehem star winning diagonal --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.6">
                        {{-- Grid lines --}}
                        <line x1="40" y1="10" x2="40" y2="110" />
                        <line x1="80" y1="10" x2="80" y2="110" />
                        <line x1="10" y1="40" x2="110" y2="40" />
                        <line x1="10" y1="80" x2="110" y2="80" />
                        
                        {{-- Moon and Star pieces - Beautiful Bethlehem aesthetic --}}
                        <g stroke-width="2.5">
                            {{-- Moon in top-left - proper lunar eclipse --}}
                            <g>
                                <circle cx="25" cy="25" r="8" fill="hsl(var(--ink) / 0.7)" />
                                <circle cx="25" cy="25" r="8" fill="hsl(var(--space-900))" clip-path="circle(50% at 15% 50%)" />
                            </g>
                            
                            {{-- Star in top-center - Bethlehem style with exaggerated vertical --}}
                            <g>
                                <circle cx="60" cy="25" r="3" fill="hsl(60 100% 95%)" />
                                <line x1="60" y1="12" x2="60" y2="38" stroke="hsl(var(--star))" stroke-width="2.5" />
                                <line x1="50" y1="25" x2="70" y2="25" stroke="hsl(var(--star))" stroke-width="2" />
                                <line x1="54" y1="19" x2="66" y2="31" stroke="hsl(var(--star))" stroke-width="1.5" />
                                <line x1="66" y1="19" x2="54" y2="31" stroke="hsl(var(--star))" stroke-width="1.5" />
                            </g>
                            
                            {{-- Moon in top-right --}}
                            <g>
                                <circle cx="95" cy="25" r="8" fill="hsl(var(--ink) / 0.7)" />
                                <circle cx="95" cy="25" r="8" fill="hsl(var(--space-900))" clip-path="circle(50% at 15% 50%)" />
                            </g>
                            
                            {{-- Star in center-left --}}
                            <g>
                                <circle cx="25" cy="60" r="3" fill="hsl(60 100% 95%)" />
                                <line x1="25" y1="47" x2="25" y2="73" stroke="hsl(var(--star))" stroke-width="2.5" />
                                <line x1="15" y1="60" x2="35" y2="60" stroke="hsl(var(--star))" stroke-width="2" />
                                <line x1="19" y1="54" x2="31" y2="66" stroke="hsl(var(--star))" stroke-width="1.5" />
                                <line x1="31" y1="54" x2="19" y2="66" stroke="hsl(var(--star))" stroke-width="1.5" />
                            </g>
                            
                            {{-- Moon in center --}}
                            <g>
                                <circle cx="60" cy="60" r="8" fill="hsl(var(--ink) / 0.7)" />
                                <circle cx="60" cy="60" r="8" fill="hsl(var(--space-900))" clip-path="circle(50% at 15% 50%)" />
                            </g>
                            
                            {{-- Star in center-right --}}
                            <g>
                                <circle cx="95" cy="60" r="3" fill="hsl(60 100% 95%)" />
                                <line x1="95" y1="47" x2="95" y2="73" stroke="hsl(var(--star))" stroke-width="2.5" />
                                <line x1="85" y1="60" x2="105" y2="60" stroke="hsl(var(--star))" stroke-width="2" />
                                <line x1="89" y1="54" x2="101" y2="66" stroke="hsl(var(--star))" stroke-width="1.5" />
                                <line x1="101" y1="54" x2="89" y2="66" stroke="hsl(var(--star))" stroke-width="1.5" />
                            </g>
                            
                            {{-- Moon in bottom-left --}}
                            <g>
                                <circle cx="25" cy="95" r="8" fill="hsl(var(--ink) / 0.7)" />
                                <circle cx="25" cy="95" r="8" fill="hsl(var(--space-900))" clip-path="circle(50% at 15% 50%)" />
                            </g>
                            
                            {{-- Star in bottom-center --}}
                            <g>
                                <circle cx="60" cy="95" r="3" fill="hsl(60 100% 95%)" />
                                <line x1="60" y1="82" x2="60" y2="108" stroke="hsl(var(--star))" stroke-width="2.5" />
                                <line x1="50" y1="95" x2="70" y2="95" stroke="hsl(var(--star))" stroke-width="2" />
                                <line x1="54" y1="89" x2="66" y2="101" stroke="hsl(var(--star))" stroke-width="1.5" />
                                <line x1="66" y1="89" x2="54" y2="101" stroke="hsl(var(--star))" stroke-width="1.5" />
                            </g>
                            
                            {{-- Moon in bottom-right --}}
                            <g>
                                <circle cx="95" cy="95" r="8" fill="hsl(var(--ink) / 0.7)" />
                                <circle cx="95" cy="95" r="8" fill="hsl(var(--space-900))" clip-path="circle(50% at 15% 50%)" />
                            </g>
                        </g>
                        
                        {{-- Winning diagonal line with Bethlehem star glow --}}
                        <defs>
                            <filter id="starGlow" x="-50%" y="-50%" width="200%" height="200%">
                                <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                <feMerge> 
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <line x1="15" y1="15" x2="105" y2="105" stroke="hsl(var(--star))" stroke-width="4" stroke-linecap="round" filter="url(#starGlow)" />
                    </g>
                </svg>
                @break

            @case('chess')
                {{-- Chess knight from Kenney assets --}}
                <img src="{{ asset('images/games/chess_knight.png') }}" 
                     alt="Chess Knight" 
                     class="w-16 h-16 opacity-70 filter brightness-0 invert" 
                     style="filter: brightness(0) invert(1) opacity(0.7);" />
                @break

            @case('checkers')
                {{-- Checkers pawns from Kenney assets --}}
                <div class="flex items-center justify-center gap-1 opacity-70">
                    <img src="{{ asset('images/games/pawn.png') }}" 
                         alt="Checkers Pawn" 
                         class="w-8 h-8 filter brightness-0 invert" 
                         style="filter: brightness(0) invert(1) opacity(0.7);" />
                    <img src="{{ asset('images/games/chess_pawn.png') }}" 
                         alt="Checkers Pawn" 
                         class="w-8 h-8 filter brightness-0 invert" 
                         style="filter: brightness(0) invert(1) opacity(0.7);" />
                </div>
                @break

            @case('connect4')
                {{-- Connect 4 grid with winning connection --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    {{-- Grid circles --}}
                    @for($row = 0; $row < 4; $row++)
                        @for($col = 0; $col < 4; $col++)
                            <circle cx="{{ 20 + $col * 26 }}" cy="{{ 20 + $row * 26 }}" r="10" 
                                    fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
                        @endfor
                    @endfor
                    
                    {{-- Game pieces --}}
                    <g>
                        {{-- Red pieces (winning diagonal) --}}
                        <circle cx="20" cy="98" r="8" fill="hsl(var(--game-red))" />
                        <circle cx="46" cy="72" r="8" fill="hsl(var(--game-red))" />
                        <circle cx="72" cy="46" r="8" fill="hsl(var(--game-red))" />
                        <circle cx="98" cy="20" r="8" fill="hsl(var(--game-red))" />
                        
                        {{-- Yellow pieces --}}
                        <circle cx="20" cy="72" r="8" fill="hsl(var(--star))" />
                        <circle cx="46" cy="46" r="8" fill="hsl(var(--star))" />
                        <circle cx="72" cy="20" r="8" fill="hsl(var(--star))" />
                    </g>
                    
                    {{-- Winning diagonal line --}}
                    <line x1="12" y1="90" x2="108" y2="12" stroke="hsl(var(--game-red))" stroke-width="3" stroke-linecap="round" />
                </svg>
                @break

            @case('puzzle')
            @case('sudoku')
                {{-- Sudoku 4x4 grid with numbers --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-linecap="round">
                        {{-- Thick lines for 2x2 regions --}}
                        <g stroke-width="2" opacity="0.8">
                            <line x1="10" y1="10" x2="10" y2="110" />
                            <line x1="110" y1="10" x2="110" y2="110" />
                            <line x1="10" y1="10" x2="110" y2="10" />
                            <line x1="10" y1="110" x2="110" y2="110" />
                            <line x1="60" y1="10" x2="60" y2="110" />
                            <line x1="10" y1="60" x2="110" y2="60" />
                        </g>
                        {{-- Thin lines for cells --}}
                        <g stroke-width="1" opacity="0.4">
                            <line x1="35" y1="10" x2="35" y2="110" />
                            <line x1="85" y1="10" x2="85" y2="110" />
                            <line x1="10" y1="35" x2="110" y2="35" />
                            <line x1="10" y1="85" x2="110" y2="85" />
                        </g>
                    </g>
                    
                    {{-- Numbers --}}
                    <g fill="currentColor" font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle">
                        {{-- Top-left 2x2 region --}}
                        <text x="22" y="22">3</text>
                        <text x="47" y="22">1</text>
                        <text x="22" y="47">4</text>
                        <text x="47" y="47">2</text>
                        
                        {{-- Top-right 2x2 region --}}
                        <text x="72" y="22">2</text>
                        <text x="97" y="22">4</text>
                        <text x="72" y="47">1</text>
                        <text x="97" y="47">3</text>
                        
                        {{-- Bottom-left 2x2 region --}}
                        <text x="22" y="72">1</text>
                        <text x="47" y="72">3</text>
                        <text x="22" y="97">2</text>
                        <text x="47" y="97">4</text>
                        
                        {{-- Bottom-right 2x2 region --}}
                        <text x="72" y="72">4</text>
                        <text x="97" y="72">2</text>
                        <text x="72" y="97">3</text>
                        <text x="97" y="97">1</text>
                    </g>
                </svg>
                @break

            @case('minesweeper')
                {{-- Minesweeper grid with active game --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.6">
                        @for($i = 0; $i < 5; $i++)
                            <line x1="10" y1="{{ 10 + $i * 25 }}" x2="110" y2="{{ 10 + $i * 25 }}" />
                            <line x1="{{ 10 + $i * 25 }}" y1="10" x2="{{ 10 + $i * 25 }}" y2="110" />
                        @endfor
                    </g>
                    
                    {{-- Revealed cells with numbers --}}
                    <g fill="currentColor" font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle">
                        <text x="22" y="22">1</text>
                        <text x="47" y="22">2</text>
                        <text x="72" y="22">1</text>
                        <text x="97" y="22">1</text>
                        <text x="22" y="47">2</text>
                        <text x="47" y="47">3</text>
                        <text x="72" y="47">2</text>
                        <text x="97" y="47">1</text>
                        <text x="22" y="72">1</text>
                        <text x="47" y="72">2</text>
                        <text x="72" y="72">1</text>
                        <text x="97" y="72">1</text>
                    </g>
                    
                    {{-- Flag --}}
                    <g fill="hsl(var(--game-red))">
                        <polygon points="70,45 70,30 85,37.5" />
                        <rect x="68" y="45" width="2" height="20"/>
                    </g>
                    
                    {{-- Mine (exploded) --}}
                    <g fill="hsl(var(--game-red))">
                        <circle cx="97" cy="97" r="8" />
                        <g stroke="hsl(var(--game-red))" stroke-width="2">
                            <line x1="89" y1="89" x2="105" y2="105" />
                            <line x1="105" y1="89" x2="89" y2="105" />
                            <line x1="97" y1="89" x2="97" y2="105" />
                            <line x1="89" y1="97" x2="105" y2="97" />
                        </g>
                    </g>
                </svg>
                @break

            @case('snake')
                {{-- Snake with food --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    {{-- Snake body segments --}}
                    <g fill="currentColor">
                        <circle cx="20" cy="60" r="8" />
                        <circle cx="35" cy="60" r="8" />
                        <circle cx="50" cy="60" r="8" />
                        <circle cx="65" cy="60" r="8" />
                        <circle cx="80" cy="60" r="8" />
                        <circle cx="95" cy="60" r="8" />
                    </g>
                    
                    {{-- Snake head --}}
                    <g fill="hsl(var(--game-green))">
                        <circle cx="110" cy="60" r="10" />
                        <circle cx="115" cy="55" r="2" fill="hsl(var(--ink))" />
                        <circle cx="115" cy="65" r="2" fill="hsl(var(--ink))" />
                    </g>
                    
                    {{-- Food --}}
                    <g fill="hsl(var(--game-red))">
                        <circle cx="30" cy="30" r="6" />
                        <circle cx="90" cy="90" r="6" />
                    </g>
                    
                    {{-- Score text --}}
                    <text x="60" y="20" text-anchor="middle" font-family="monospace" font-size="8" fill="currentColor">Score: 12</text>
                </svg>
                @break

            @case('cards')
            @case('solitaire')
                {{-- Playing cards stack --}}
                <svg width="60" height="80" viewBox="0 0 100 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        <rect x="15" y="35" width="45" height="65" rx="4"/>
                        <rect x="25" y="25" width="45" height="65" rx="4"/>
                        <rect x="35" y="15" width="45" height="65" rx="4"/>
                    </g>
                </svg>
                @break

            @case('memory')
                {{-- Memory card pairs --}}
                <svg width="80" height="60" viewBox="0 0 120 100" class="opacity-70 text-ink/70" aria-hidden="true">
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
                {{-- 2048 grid with 4 cells --}}
                <svg width="100" height="100" viewBox="0 0 120 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.6">
                        {{-- 2x2 grid --}}
                        <rect x="10" y="10" width="50" height="50" rx="3"/>
                        <rect x="60" y="10" width="50" height="50" rx="3"/>
                        <rect x="10" y="60" width="50" height="50" rx="3"/>
                        <rect x="60" y="60" width="50" height="50" rx="3"/>
                    </g>
                    
                    {{-- Game tiles with numbers --}}
                    <g font-family="monospace" font-size="10" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                        {{-- Top-left: 2 --}}
                        <rect x="12" y="12" width="46" height="46" rx="3" fill="hsl(var(--surface) / .1)" />
                        <text x="35" y="35" fill="hsl(var(--ink-muted))">2</text>
                        
                        {{-- Top-right: blank (empty) --}}
                        <rect x="62" y="12" width="46" height="46" rx="3" fill="hsl(var(--surface) / .05)" />
                        
                        {{-- Bottom-left: 4 --}}
                        <rect x="12" y="62" width="46" height="46" rx="3" fill="hsl(var(--surface) / .2)" />
                        <text x="35" y="85" fill="hsl(var(--ink-muted))">4</text>
                        
                        {{-- Bottom-right: 8 --}}
                        <rect x="62" y="62" width="46" height="46" rx="3" fill="hsl(var(--star) / .3)" />
                        <text x="85" y="85" fill="hsl(var(--ink))">8</text>
                    </g>
                </svg>
                @break

            @case('board')
                {{-- Generic board game --}}
                <x-heroicon-o-rectangle-group class="w-16 h-16 text-ink/70" />
                @break

            @default
                {{-- Fallback sparkles --}}
                <x-heroicon-o-sparkles class="w-10 h-10 text-ink/70" />
        @endswitch
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
            transition: border-color .15s ease;
        }
        
        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .um-game-card,
            .um-game-card .um-title {
                transition: none;
            }
        }
    </style>
    @endpush
@endonce
