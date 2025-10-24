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
    
    {{-- Visual motif layer --}}
    <div class="um-motif absolute inset-0 grid place-items-center p-4 md:p-6">
        @switch($motif)
            @case('tictactoe')
                {{-- Tic-tac-toe: 3 stars in diagonal (top-left to bottom-right), 2 moons in other positions --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- Grid lines --}}
                    <g stroke="currentColor" stroke-width="1.5" opacity="0.4">
                        <line x1="0" y1="33.33" x2="100" y2="33.33" />
                        <line x1="0" y1="66.67" x2="100" y2="66.67" />
                        <line x1="33.33" y1="0" x2="33.33" y2="100" />
                        <line x1="66.67" y1="0" x2="66.67" y2="100" />
                    </g>

                    {{-- Top-left star --}}
                    <g transform="translate(16.67, 16.67)">
                        <circle cx="0" cy="0" r="3" fill="hsl(var(--star))" />
                        <line x1="0" y1="-12" x2="0" y2="12" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-12" y1="0" x2="12" y2="0" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-8.5" y1="-8.5" x2="8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                        <line x1="8.5" y1="-8.5" x2="-8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                    </g>

                    {{-- Center star --}}
                    <g transform="translate(50, 50)">
                        <circle cx="0" cy="0" r="3" fill="hsl(var(--star))" />
                        <line x1="0" y1="-12" x2="0" y2="12" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-12" y1="0" x2="12" y2="0" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-8.5" y1="-8.5" x2="8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                        <line x1="8.5" y1="-8.5" x2="-8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                    </g>

                    {{-- Bottom-right star --}}
                    <g transform="translate(83.33, 83.33)">
                        <circle cx="0" cy="0" r="3" fill="hsl(var(--star))" />
                        <line x1="0" y1="-12" x2="0" y2="12" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-12" y1="0" x2="12" y2="0" stroke="hsl(var(--star))" stroke-width="2" />
                        <line x1="-8.5" y1="-8.5" x2="8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                        <line x1="8.5" y1="-8.5" x2="-8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                    </g>

                    {{-- Top-right moon --}}
                    <g transform="translate(83.33, 16.67)">
                        <circle cx="0" cy="0" r="8" fill="hsl(var(--ink))" />
                        <circle cx="0" cy="0" r="8" fill="hsl(var(--space-900))" clip-path="circle(65% at 35% 50%)" />
                    </g>

                    {{-- Bottom-left moon --}}
                    <g transform="translate(16.67, 83.33)">
                        <circle cx="0" cy="0" r="8" fill="hsl(var(--ink))" />
                        <circle cx="0" cy="0" r="8" fill="hsl(var(--space-900))" clip-path="circle(65% at 35% 50%)" />
                    </g>
                </svg>
                @break

            @case('chess')
                {{-- Chess: knight piece on checkered board --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- 8x8 checkered board --}}
                    <g>
                        @for($row = 0; $row < 8; $row++)
                            @for($col = 0; $col < 8; $col++)
                                @if(($row + $col) % 2 === 0)
                                    <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="hsl(45 25% 85%)" />
                                @else
                                    <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="hsl(25 15% 35%)" />
                                @endif
                            @endfor
                        @endfor
                    </g>

                    {{-- Chess knight piece --}}
                    <g fill="hsl(var(--ink))" transform="translate(50, 50)">
                        {{-- Knight body --}}
                        <ellipse cx="0" cy="5" rx="8" ry="12" />
                        {{-- Knight head --}}
                        <circle cx="0" cy="-8" r="6" />
                        {{-- Knight mane --}}
                        <path d="M-4,-12 Q0,-16 4,-12 Q2,-10 0,-8 Q-2,-10 -4,-12" />
                        {{-- Knight ears --}}
                        <ellipse cx="-3" cy="-10" rx="1.5" ry="3" transform="rotate(-20)" />
                        <ellipse cx="3" cy="-10" rx="1.5" ry="3" transform="rotate(20)" />
                        {{-- Knight eye --}}
                        <circle cx="1" cy="-9" r="1" fill="hsl(var(--ink))" />
                        {{-- Knight nose --}}
                        <ellipse cx="0" cy="-6" rx="0.8" ry="1.5" />
                    </g>
                </svg>
                @break

            @case('checkers')
                {{-- Checkers: alternating red and black pieces on checkered board --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- 8x8 checkered board pattern --}}
                    <g>
                        @for($row = 0; $row < 8; $row++)
                            @for($col = 0; $col < 8; $col++)
                                @if(($row + $col) % 2 === 0)
                                    <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="hsl(45 15% 75%)" />
                                @else
                                    <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="hsl(220 15% 25%)" />
                                @endif
                            @endfor
                        @endfor
                    </g>

                    {{-- Checkers pieces - starting position --}}
                    {{-- Black pieces (top 3 rows) --}}
                    <circle cx="18.75" cy="18.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="43.75" cy="18.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="68.75" cy="18.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="93.75" cy="18.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />

                    <circle cx="6.25" cy="31.25" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="31.25" cy="31.25" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="56.25" cy="31.25" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="81.25" cy="31.25" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />

                    <circle cx="18.75" cy="43.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="43.75" cy="43.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="68.75" cy="43.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="93.75" cy="43.75" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />

                    {{-- Red pieces (bottom 3 rows) --}}
                    <circle cx="6.25" cy="56.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="31.25" cy="56.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="56.25" cy="56.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="81.25" cy="56.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />

                    <circle cx="18.75" cy="68.75" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="43.75" cy="68.75" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="68.75" cy="68.75" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="93.75" cy="68.75" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />

                    <circle cx="6.25" cy="81.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="31.25" cy="81.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="56.25" cy="81.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                    <circle cx="81.25" cy="81.25" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                </svg>
                @break

            @case('connect4')
                {{-- Connect 4: 4x4 grid with pieces and winning line --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- Grid circles --}}
                    @for($row = 0; $row < 4; $row++)
                        @for($col = 0; $col < 4; $col++)
                            <circle cx="{{ 15 + $col * 21 }}" cy="{{ 15 + $row * 21 }}" r="9"
                                    fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
                        @endfor
                    @endfor

                    {{-- Game pieces --}}
                    <g>
                        {{-- Yellow pieces (winning diagonal) --}}
                        <circle cx="15" cy="79" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                        <circle cx="36" cy="58" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                        <circle cx="57" cy="37" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                        <circle cx="78" cy="16" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />

                        {{-- Red pieces --}}
                        <circle cx="15" cy="58" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                        <circle cx="36" cy="37" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                        <circle cx="57" cy="16" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                    </g>

                    {{-- Winning diagonal line --}}
                    <line x1="15" y1="79" x2="78" y2="16" stroke="hsl(var(--star))" stroke-width="2" stroke-linecap="round" opacity="0.8" />
                </svg>
                @break

            @case('puzzle')
            @case('sudoku')
                {{-- Sudoku: 9x9 grid with highlighted 3x3 box --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    <g stroke="currentColor" stroke-linecap="round">
                        {{-- Outer border --}}
                        <g stroke-width="2" opacity="0.8">
                            <line x1="5" y1="5" x2="5" y2="95" />
                            <line x1="95" y1="5" x2="95" y2="95" />
                            <line x1="5" y1="5" x2="95" y2="5" />
                            <line x1="5" y1="95" x2="95" y2="95" />
                        </g>

                        {{-- 3x3 box border (thicker, highlighted) --}}
                        <g stroke-width="3" opacity="1" stroke="hsl(var(--star))">
                            <line x1="5" y1="5" x2="5" y2="70" />
                            <line x1="70" y1="5" x2="70" y2="70" />
                            <line x1="5" y1="5" x2="70" y2="5" />
                            <line x1="5" y1="70" x2="70" y2="70" />
                        </g>

                        {{-- Regular grid lines --}}
                        <g stroke-width="1" opacity="0.4">
                            <line x1="27" y1="5" x2="27" y2="95" />
                            <line x1="48" y1="5" x2="48" y2="95" />
                            <line x1="70" y1="5" x2="70" y2="95" />
                            <line x1="5" y1="27" x2="95" y2="27" />
                            <line x1="5" y1="48" x2="95" y2="48" />
                            <line x1="5" y1="70" x2="95" y2="70" />
                        </g>
                    </g>

                    {{-- Numbers in 3x3 highlighted box --}}
                    <g fill="hsl(var(--star))" font-family="monospace" font-size="6" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                        <text x="16" y="16">5</text>
                        <text x="37" y="16">3</text>
                        <text x="59" y="16">2</text>
                        <text x="16" y="37">6</text>
                        <text x="37" y="37">1</text>
                        <text x="59" y="37">9</text>
                        <text x="16" y="59">8</text>
                        <text x="37" y="59">9</text>
                        <text x="59" y="59">8</text>
                    </g>

                    {{-- Numbers outside 3x3 box (muted) --}}
                    <g fill="currentColor" font-family="monospace" font-size="5" text-anchor="middle" dominant-baseline="middle" opacity="0.4">
                        <text x="81" y="16">1</text>
                        <text x="81" y="37">3</text>
                        <text x="16" y="81">1</text>
                        <text x="37" y="81">3</text>
                        <text x="59" y="81">1</text>
                        <text x="81" y="81">1</text>
                    </g>
                </svg>
                @break

            @case('minesweeper')
                {{-- Minesweeper: 4x4 grid with mines, numbers, and flag --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- Grid lines --}}
                    <g stroke="currentColor" stroke-width="1" fill="none" opacity="0.5">
                        <line x1="0" y1="25" x2="100" y2="25" />
                        <line x1="0" y1="50" x2="100" y2="50" />
                        <line x1="0" y1="75" x2="100" y2="75" />
                        <line x1="25" y1="0" x2="25" y2="100" />
                        <line x1="50" y1="0" x2="50" y2="100" />
                        <line x1="75" y1="0" x2="75" y2="100" />
                    </g>

                    {{-- Background cells --}}
                    <g fill="hsl(var(--surface) / .1)" opacity="0.8">
                        <rect x="2" y="2" width="21" height="21" />
                        <rect x="27" y="2" width="21" height="21" />
                        <rect x="52" y="2" width="21" height="21" />
                        <rect x="77" y="2" width="21" height="21" />
                        <rect x="2" y="27" width="21" height="21" />
                        <rect x="27" y="27" width="21" height="21" />
                        <rect x="52" y="27" width="21" height="21" />
                        <rect x="77" y="27" width="21" height="21" />
                        <rect x="2" y="52" width="21" height="21" />
                        <rect x="27" y="52" width="21" height="21" />
                        <rect x="52" y="52" width="21" height="21" />
                        <rect x="77" y="52" width="21" height="21" />
                        <rect x="2" y="77" width="21" height="21" />
                        <rect x="27" y="77" width="21" height="21" />
                        <rect x="52" y="77" width="21" height="21" />
                        <rect x="77" y="77" width="21" height="21" />
                    </g>

                    {{-- Numbers in revealed cells --}}
                    <g fill="hsl(210 100% 50%)" font-family="monospace" font-size="7" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                        <text x="12.5" y="12.5">1</text>
                        <text x="37.5" y="12.5">2</text>
                        <text x="62.5" y="12.5">1</text>
                        <text x="87.5" y="12.5">1</text>
                        <text x="12.5" y="37.5">2</text>
                        <text x="37.5" y="37.5">3</text>
                        <text x="62.5" y="37.5">2</text>
                        <text x="87.5" y="37.5">1</text>
                        <text x="12.5" y="62.5">1</text>
                        <text x="37.5" y="62.5">2</text>
                        <text x="62.5" y="62.5">1</text>
                        <text x="87.5" y="62.5">1</text>
                    </g>

                    {{-- Flag in top-right --}}
                    <g fill="hsl(var(--game-red))" transform="translate(87.5, 37.5)">
                        <polygon points="0,-7.5 0,-15 10,-10" />
                        <rect x="-1" y="-7.5" width="1" height="10"/>
                    </g>

                    {{-- Mine in bottom-right --}}
                    <g fill="hsl(var(--game-red))" transform="translate(87.5, 87.5)">
                        <circle cx="0" cy="0" r="6" />
                        <g stroke="hsl(var(--game-red))" stroke-width="1.5">
                            <line x1="-4" y1="-4" x2="4" y2="4" />
                            <line x1="4" y1="-4" x2="-4" y2="4" />
                            <line x1="0" y1="-4" x2="0" y2="4" />
                            <line x1="-4" y1="0" x2="4" y2="0" />
                        </g>
                    </g>
                </svg>
                @break

            @case('snake')
                {{-- Snake: simple snake path with head and food --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    {{-- Snake body path --}}
                    <path d="M15 50 Q25 30 35 50 Q45 70 55 50 Q65 30 75 50 Q85 70 90 50"
                          stroke="hsl(var(--constellation))"
                          stroke-width="3"
                          fill="none"
                          opacity="0.8" />

                    {{-- Snake body segments --}}
                    <g fill="hsl(var(--constellation))" opacity="0.9">
                        <circle cx="15" cy="50" r="3" />
                        <circle cx="35" cy="50" r="3" />
                        <circle cx="55" cy="50" r="3" />
                        <circle cx="75" cy="50" r="3" />
                    </g>

                    {{-- Snake head --}}
                    <g fill="hsl(var(--star))">
                        <circle cx="90" cy="50" r="4" />
                        <circle cx="92" cy="47" r="1" fill="hsl(var(--ink))" />
                        <circle cx="92" cy="53" r="1" fill="hsl(var(--ink))" />
                    </g>

                    {{-- Food apples --}}
                    <g fill="hsl(var(--game-red))">
                        <circle cx="25" cy="25" r="3" />
                        <circle cx="85" cy="75" r="3" />
                        {{-- Apple stems --}}
                        <rect x="24" y="22" width="1" height="3" fill="hsl(var(--ink))" />
                        <rect x="84" y="72" width="1" height="3" fill="hsl(var(--ink))" />
                    </g>
                </svg>
                @break

            @case('cards')
            @case('solitaire')
                {{-- Playing cards stack --}}
                <svg width="80" height="100" viewBox="0 0 100 120" class="opacity-70 text-ink/70" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="2" fill="none">
                        <rect x="15" y="35" width="45" height="65" rx="4"/>
                        <rect x="25" y="25" width="45" height="65" rx="4"/>
                        <rect x="35" y="15" width="45" height="65" rx="4"/>
                    </g>
                </svg>
                @break

            @case('memory')
                {{-- Memory card pairs --}}
                <svg width="100" height="80" viewBox="0 0 120 100" class="opacity-70 text-ink/70" aria-hidden="true">
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
                {{-- 2048 grid with 4 cells --}}
                <svg width="100" height="100" viewBox="0 0 100 100" class="opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" aria-hidden="true">
                    <g stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.6">
                        {{-- 2x2 grid --}}
                        <rect x="8" y="8" width="42" height="42" rx="3"/>
                        <rect x="50" y="8" width="42" height="42" rx="3"/>
                        <rect x="8" y="50" width="42" height="42" rx="3"/>
                        <rect x="50" y="50" width="42" height="42" rx="3"/>
                    </g>

                    {{-- Game tiles with numbers --}}
                    <g font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                        {{-- Top-left: 2 --}}
                        <rect x="10" y="10" width="38" height="38" rx="2" fill="hsl(var(--surface) / .15)" />
                        <text x="29" y="29" fill="hsl(var(--ink-muted))">2</text>

                        {{-- Top-right: blank (empty) --}}
                        <rect x="52" y="10" width="38" height="38" rx="2" fill="hsl(var(--surface) / .08)" />

                        {{-- Bottom-left: 4 --}}
                        <rect x="10" y="52" width="38" height="38" rx="2" fill="hsl(var(--surface) / .25)" />
                        <text x="29" y="71" fill="hsl(var(--ink-muted))">4</text>

                        {{-- Bottom-right: 8 --}}
                        <rect x="52" y="52" width="38" height="38" rx="2" fill="hsl(var(--star) / .4)" />
                        <text x="71" y="71" fill="hsl(var(--ink))">8</text>
                    </g>
                </svg>
                @break

            @case('board')
                {{-- Generic board game --}}
                <x-heroicon-o-rectangle-group class="w-32 h-32 text-ink/70" />
                @break

            @default
                {{-- Fallback sparkles --}}
                <x-heroicon-o-sparkles class="w-20 h-20 text-ink/70" />
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
