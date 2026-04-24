@props([
    'motif' => 'sparkles',
    'class' => 'opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20',
])

@php
    $svg = $class;
@endphp

{{-- Ursa Minor: crisp puzzle-style game icons. Single source for cards + about rows. --}}
<div {{ $attributes->merge(['class' => 'um-game-motif flex items-center justify-center w-full h-full min-h-0']) }}>

    @switch($motif)
        @case('tictactoe')
            {{-- 3x3 grid, five-point star rays on the diagonal, crescent moons in the two off-diagonal corners --}}
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g stroke="currentColor" stroke-width="1" opacity="0.4" fill="none" stroke-linecap="square">
                    <line x1="0" y1="33.3" x2="100" y2="33.3" />
                    <line x1="0" y1="66.6" x2="100" y2="66.6" />
                    <line x1="33.3" y1="0" x2="33.3" y2="100" />
                    <line x1="66.6" y1="0" x2="66.6" y2="100" />
                </g>
                @foreach([[16.67,16.67],[50,50],[83.33,83.33]] as $pos)
                <g transform="translate({{ $pos[0] }},{{ $pos[1] }})" fill="none" stroke="hsl(var(--star))" stroke-width="1.2" stroke-linecap="round">
                    @for($a = 0; $a < 5; $a++)
                    <g transform="rotate({{ -90 + $a * 72 }})">
                        <line x1="0" y1="0" x2="0" y2="-4.2" />
                    </g>
                    @endfor
                </g>
                @endforeach
                <g fill="hsl(var(--ink) / 0.95)" transform="translate(83.33,16.67)">
                    <circle r="5.2" />
                    <circle cx="0.8" r="4.6" fill="hsl(var(--space-500))" />
                </g>
                <g fill="hsl(var(--ink) / 0.95)" transform="translate(16.67,83.33)">
                    <circle r="5.2" />
                    <circle cx="0.8" r="4.6" fill="hsl(var(--space-500))" />
                </g>
            </svg>
            @break

        @case('chess')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g>
                    @for($r = 0; $r < 8; $r++)
                        @for($c = 0; $c < 8; $c++)
                            <rect x="{{ 6 + $c * 10.5 }}" y="{{ 6 + $r * 10.5 }}" width="10.5" height="10.5" rx="0.5" fill="{{ ($r + $c) % 2 ? 'hsl(26 20% 28%)' : 'hsl(42 32% 88%)' }}" />
                        @endfor
                    @endfor
                </g>
                {{-- Classic side-view knight, centered on a light cell --}}
                <g fill="hsl(220 25% 18%)" transform="translate(50, 52) scale(0.85)">
                    <path d="M-2 14 L-8 6 Q-8 -2 0-10 Q4-12 8-8 L10 0 Q14 2 10 6 L4 4 Q6 8 2 10 Z" />
                    <path d="M-4 -6 L2-14 Q6-16 10-10" fill="none" stroke="hsl(220 25% 12%)" stroke-width="1" stroke-linecap="round" />
                </g>
            </svg>
            @break

        @case('checkers')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g>
                    @for($r = 0; $r < 8; $r++)
                        @for($c = 0; $c < 8; $c++)
                            <rect x="{{ 6 + $c * 10.5 }}" y="{{ 6 + $r * 10.5 }}" width="10.5" height="10.5" fill="{{ ($r + $c) % 2 ? 'hsl(28 32% 22%)' : 'hsl(38 20% 78%)' }}" />
                        @endfor
                    @endfor
                </g>
                @foreach([[6+10.5*1+5.25,6+1*10.5+5.25],[6+3*10.5+5.25,6+1*10.5+5.25],[6+5*10.5+5.25,6+1*10.5+5.25],[6+0*10.5+5.25,6+2*10.5+5.25]] as $p)
                    <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3.6" fill="hsl(220 25% 14%)" stroke="hsl(42 20% 40%)" stroke-width="0.5" />
                @endforeach
                @foreach([[6+0*10.5+5.25,6+5*10.5+5.25],[6+2*10.5+5.25,6+5*10.5+5.25],[6+4*10.5+5.25,6+5*10.5+5.25],[6+1*10.5+5.25,6+6*10.5+5.25]] as $p)
                    <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3.6" fill="hsl(var(--game-red))" stroke="hsl(0 0% 100% / 0.25)" stroke-width="0.4" />
                @endforeach
            </svg>
            @break

        @case('connect4')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <rect x="4" y="4" width="92" height="86" rx="3" fill="hsl(212 50% 28%)" />
                <g>
                    @for($row = 0; $row < 6; $row++)
                        @for($col = 0; $col < 7; $col++)
                            <circle
                                cx="{{ 11.5 + $col * 12.4 }}"
                                cy="{{ 12 + (5 - $row) * 12.4 }}"
                                r="4.4"
                                fill="hsl(212 45% 18%)"
                                stroke="hsl(0 0% 100% / 0.08)"
                                stroke-width="0.5" />
                        @endfor
                    @endfor
                </g>
                {{-- Sample pieces: yellow / red diagonal like "four in a row" --}}
                <circle cx="11.5" cy="12" r="3.2" fill="hsl(var(--game-yellow))" />
                <circle cx="11.5" cy="24.4" r="3.2" fill="hsl(var(--game-red))" />
                <circle cx="23.9" cy="12" r="3.2" fill="hsl(var(--game-red))" />
                <circle cx="23.9" cy="24.4" r="3.2" fill="hsl(var(--game-yellow))" />
            </svg>
            @break

        @case('puzzle')
        @case('sudoku')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <rect x="2" y="2" width="96" height="96" fill="hsl(220 45% 6% / 0.2)" stroke="hsl(var(--ink) / 0.3)" stroke-width="0.8" rx="1" />
                <g fill="none" stroke-linecap="round">
                    @for($i = 1; $i < 9; $i++)
                    @php
                        $g = 2 + ($i * 96) / 9;
                        $thick = ($i % 3 === 0) ? 1.1 : 0.4;
                        $c = $i % 3 === 0 ? '0.5' : '0.2';
                    @endphp
                    <line x1="{{ $g }}" y1="2" x2="{{ $g }}" y2="98" stroke="hsl(var(--ink) / {{ $c }})" stroke-width="{{ $thick }}" />
                    <line y1="{{ $g }}" x1="2" y2="{{ $g }}" x2="98" stroke="hsl(var(--ink) / {{ $c }})" stroke-width="{{ $thick }}" />
                    @endfor
                </g>
                <text x="8" y="20" font-size="9" font-weight="600" font-family="Oswald, system-ui" fill="hsl(var(--star))">1</text>
                <text x="22" y="20" font-size="9" font-weight="500" font-family="Oswald, system-ui" fill="hsl(var(--ink) / 0.5)">4</text>
                <text x="8" y="32" font-size="9" font-weight="500" font-family="Oswald, system-ui" fill="hsl(var(--ink) / 0.4)">2</text>
            </svg>
            @break

        @case('minesweeper')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g stroke="hsl(var(--ink) / 0.2)" fill="hsl(var(--space-700) / 0.4)">
                    @for($y = 0; $y < 4; $y++)
                        @for($x = 0; $x < 4; $x++)
                            <rect x="{{ 4 + $x * 22.5 }}" y="{{ 4 + $y * 22.5 }}" width="21" height="21" rx="1" stroke-width="0.4" />
                        @endfor
                    @endfor
                </g>
                <text x="16" y="22" font-size="8" font-weight="700" font-family="ui-monospace, monospace" text-anchor="middle" fill="hsl(var(--game-blue))">1</text>
                <text x="38" y="22" font-size="8" font-weight="700" font-family="ui-monospace, monospace" text-anchor="middle" fill="hsl(var(--game-green))">2</text>
                <text x="16" y="45" font-size="8" font-weight="700" font-family="ui-monospace, monospace" text-anchor="middle" fill="hsl(var(--game-red))">3</text>
                <g transform="translate(60, 38)">
                    <line x1="0" y1="8" x2="0" y2="0" stroke="hsl(40 20% 35%)" stroke-width="1" />
                    <path d="M-6 0 L0-8 L6 0 Z" fill="hsl(var(--game-red))" />
                </g>
                <g transform="translate(82, 60)">
                    <circle r="4.5" fill="hsl(220 15% 22%)" />
                    <line x1="-2.2" y1="2.2" x2="2.2" y2="-2.2" stroke="hsl(0 0% 100% / 0.3)" />
                    <line x1="-2.2" y1="-2.2" x2="2.2" y2="2.2" stroke="hsl(0 0% 100% / 0.3)" />
                </g>
            </svg>
            @break

        @case('snake')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <rect x="6" y="6" width="88" height="88" rx="2" fill="hsl(220 45% 8% / 0.4)" stroke="hsl(var(--ink) / 0.1)" />
                <path
                    d="M18 50h18v16h20v-16h18v16H56v16H36V66H18V50Z"
                    fill="none" stroke="hsl(var(--constellation))" stroke-width="3.2" stroke-linejoin="round" />
                <circle cx="30" cy="50" r="2.2" fill="hsl(var(--constellation))" />
                <circle cx="50" cy="50" r="2.2" fill="hsl(var(--constellation))" />
                <g transform="translate(74,50)">
                    <rect x="-4" y="-4" width="8" height="8" rx="1" fill="hsl(var(--star))" />
                    <circle cx="1.2" cy="-0.5" r="0.5" fill="hsl(220 25% 12%)" />
                </g>
                <circle cx="20" cy="20" r="2.4" fill="hsl(var(--game-red))" />
            </svg>
            @break

        @case('2048')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <rect x="6" y="6" width="88" height="88" rx="4" fill="hsl(25 20% 14% / 0.5)" />
                <g>
                    <rect x="10" y="10" width="19" height="19" rx="2" fill="hsl(38 40% 72% / 0.3)" />
                    <text x="19.5" y="25" text-anchor="middle" font-size="7" font-weight="700" font-family="ui-sans-serif" fill="hsl(var(--ink) / 0.85)">2</text>
                </g>
                <g>
                    <rect x="33" y="10" width="19" height="19" rx="2" fill="hsl(38 40% 60% / 0.2)" />
                </g>
                <g>
                    <rect x="10" y="33" width="19" height="19" rx="2" fill="hsl(28 100% 68% / 0.4)" />
                    <text x="19.5" y="48" text-anchor="middle" font-size="7" font-weight="700" font-family="ui-sans-serif" fill="hsl(var(--ink) / 0.9)">4</text>
                </g>
                <g>
                    <rect x="33" y="33" width="19" height="19" rx="2" fill="hsl(var(--star) / 0.55)" />
                    <text x="42.5" y="48" text-anchor="middle" font-size="7" font-weight="700" font-family="ui-sans-serif" fill="hsl(220 25% 8%)">8</text>
                </g>
            </svg>
            @break

        @case('cards')
        @case('solitaire')
            <svg viewBox="0 0 100 120" class="{{ $svg }} max-h-full w-full" aria-hidden="true">
                <g stroke="currentColor" fill="hsl(var(--surface) / 0.2)" stroke-width="1" stroke-linejoin="round" opacity="0.85">
                    <rect x="20" y="32" width="50" height="70" rx="3" />
                    <rect x="30" y="20" width="50" height="70" rx="3" fill="hsl(var(--surface) / 0.1)" />
                    <rect x="40" y="8" width="50" height="70" rx="3" fill="hsl(var(--star) / 0.1)" />
                </g>
                <text x="65" y="26" text-anchor="middle" font-size="9" font-weight="600" fill="hsl(var(--star) / 0.75)">A</text>
            </svg>
            @break

        @case('memory')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g stroke="currentColor" fill="hsl(var(--ink) / 0.04)" stroke-width="1.2" opacity="0.9">
                    <rect x="8" y="28" width="26" height="40" rx="2" />
                    <rect x="40" y="28" width="26" height="40" rx="2" fill="hsl(var(--constellation) / 0.15)" />
                    <rect x="72" y="28" width="26" height="40" rx="2" />
                </g>
                <circle cx="20" cy="18" r="2.5" fill="hsl(var(--star))" />
                <circle cx="80" cy="18" r="2.5" fill="hsl(var(--constellation) / 0.8)" />
            </svg>
            @break

        @case('board')
            <svg viewBox="0 0 100 100" class="{{ $svg }}" aria-hidden="true">
                <g stroke="hsl(var(--ink) / 0.25)" fill="none" stroke-width="0.4">
                    @for($c = 0; $c < 5; $c++)
                        @for($r = 0; $r < 4; $r++)
                            <rect x="{{ 10 + $c * 16 }}" y="{{ 20 + $r * 16 }}" width="16" height="16" />
                        @endfor
                    @endfor
                </g>
                <text x="20" y="32" font-size="8" font-weight="600" fill="hsl(var(--star) / 0.9)">A</text>
                <text x="36" y="32" font-size="8" font-weight="600" fill="hsl(var(--ink) / 0.45)">B</text>
                <text x="20" y="50" font-size="8" font-weight="600" fill="hsl(var(--constellation) / 0.8)">D</text>
            </svg>
            @break

        @default
            <x-heroicon-o-sparkles class="w-20 h-20 text-ink/70 shrink-0" />
    @endswitch
</div>
