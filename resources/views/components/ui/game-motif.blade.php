@props([
    'motif' => 'sparkles',
    'class' => 'opacity-80 text-ink/70 w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20',
])

<div {{ $attributes->merge(['class' => 'um-motif grid place-items-center']) }}>
    @switch($motif)
        @case('tictactoe')
            {{-- Tic-tac-toe: 3 stars in diagonal, 2 moons --}}
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                <g stroke="currentColor" stroke-width="1.5" opacity="0.4">
                    <line x1="0" y1="33.33" x2="100" y2="33.33" /><line x1="0" y1="66.67" x2="100" y2="66.67" />
                    <line x1="33.33" y1="0" x2="33.33" y2="100" /><line x1="66.67" y1="0" x2="66.67" y2="100" />
                </g>
                @foreach([[16.67,16.67],[50,50],[83.33,83.33]] as $p)
                <g transform="translate({{ $p[0] }}, {{ $p[1] }})">
                    <circle cx="0" cy="0" r="3" fill="hsl(var(--star))" />
                    <line x1="0" y1="-12" x2="0" y2="12" stroke="hsl(var(--star))" stroke-width="2" />
                    <line x1="-12" y1="0" x2="12" y2="0" stroke="hsl(var(--star))" stroke-width="2" />
                    <line x1="-8.5" y1="-8.5" x2="8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                    <line x1="8.5" y1="-8.5" x2="-8.5" y2="8.5" stroke="hsl(var(--star))" stroke-width="1.5" />
                </g>
                @endforeach
                <g transform="translate(83.33, 16.67)"><circle cx="0" cy="0" r="8" fill="hsl(var(--ink))" /><circle cx="0" cy="0" r="8" fill="hsl(var(--space-900))" clip-path="circle(65% at 35% 50%)" /></g>
                <g transform="translate(16.67, 83.33)"><circle cx="0" cy="0" r="8" fill="hsl(var(--ink))" /><circle cx="0" cy="0" r="8" fill="hsl(var(--space-900))" clip-path="circle(65% at 35% 50%)" /></g>
            </svg>
            @break
        @case('chess')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                @for($row = 0; $row < 8; $row++) @for($col = 0; $col < 8; $col++)
                <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="{{ ($row + $col) % 2 === 0 ? 'hsl(45 25% 85%)' : 'hsl(25 15% 35%)' }}" />
                @endfor @endfor
                <g fill="hsl(var(--ink))" transform="translate(50, 50)">
                    <ellipse cx="0" cy="5" rx="8" ry="12" /><circle cx="0" cy="-8" r="6" />
                    <path d="M-4,-12 Q0,-16 4,-12 Q2,-10 0,-8 Q-2,-10 -4,-12" />
                    <ellipse cx="-3" cy="-10" rx="1.5" ry="3" transform="rotate(-20)" />
                    <ellipse cx="3" cy="-10" rx="1.5" ry="3" transform="rotate(20)" />
                    <circle cx="1" cy="-9" r="1" fill="hsl(var(--ink))" /><ellipse cx="0" cy="-6" rx="0.8" ry="1.5" />
                </g>
            </svg>
            @break
        @case('checkers')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                @for($row = 0; $row < 8; $row++) @for($col = 0; $col < 8; $col++)
                <rect x="{{ $col * 12.5 }}" y="{{ $row * 12.5 }}" width="12.5" height="12.5" fill="{{ ($row + $col) % 2 === 0 ? 'hsl(45 15% 75%)' : 'hsl(220 15% 25%)' }}" />
                @endfor @endfor
                @foreach([[18.75,18.75],[43.75,18.75],[68.75,18.75],[93.75,18.75],[6.25,31.25],[31.25,31.25],[56.25,31.25],[81.25,31.25],[18.75,43.75],[43.75,43.75],[68.75,43.75],[93.75,43.75]] as $p)
                <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="4" fill="hsl(var(--space-900))" stroke="hsl(var(--border))" stroke-width="0.5" />
                @endforeach
                @foreach([[6.25,56.25],[31.25,56.25],[56.25,56.25],[81.25,56.25],[18.75,68.75],[43.75,68.75],[68.75,68.75],[93.75,68.75],[6.25,81.25],[31.25,81.25],[56.25,81.25],[81.25,81.25]] as $p)
                <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="4" fill="hsl(0 70% 60%)" stroke="hsl(var(--border))" stroke-width="0.5" />
                @endforeach
            </svg>
            @break
        @case('connect4')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                @for($row = 0; $row < 4; $row++) @for($col = 0; $col < 4; $col++)
                <circle cx="{{ 15 + $col * 21 }}" cy="{{ 15 + $row * 21 }}" r="9" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
                @endfor @endfor
                <circle cx="15" cy="79" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                <circle cx="36" cy="58" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                <circle cx="57" cy="37" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                <circle cx="78" cy="16" r="7" fill="hsl(var(--star))" stroke="hsl(var(--star))" stroke-width="1" />
                <circle cx="15" cy="58" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                <circle cx="36" cy="37" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                <circle cx="57" cy="16" r="7" fill="hsl(var(--game-red))" stroke="hsl(var(--game-red))" stroke-width="1" />
                <line x1="15" y1="79" x2="78" y2="16" stroke="hsl(var(--star))" stroke-width="2" stroke-linecap="round" opacity="0.8" />
            </svg>
            @break
        @case('puzzle')
        @case('sudoku')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                <g stroke="currentColor" stroke-linecap="round">
                    <g stroke-width="2" opacity="0.8"><line x1="5" y1="5" x2="5" y2="95" /><line x1="95" y1="5" x2="95" y2="95" /><line x1="5" y1="5" x2="95" y2="5" /><line x1="5" y1="95" x2="95" y2="95" /></g>
                    <g stroke-width="3" opacity="1" stroke="hsl(var(--star))"><line x1="5" y1="5" x2="5" y2="70" /><line x1="70" y1="5" x2="70" y2="70" /><line x1="5" y1="5" x2="70" y2="5" /><line x1="5" y1="70" x2="70" y2="70" /></g>
                    <g stroke-width="1" opacity="0.4"><line x1="27" y1="5" x2="27" y2="95" /><line x1="48" y1="5" x2="48" y2="95" /><line x1="70" y1="5" x2="70" y2="95" /><line x1="5" y1="27" x2="95" y2="27" /><line x1="5" y1="48" x2="95" y2="48" /><line x1="5" y1="70" x2="95" y2="70" /></g>
                </g>
                <g fill="hsl(var(--star))" font-family="monospace" font-size="6" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                    <text x="16" y="16">5</text><text x="37" y="16">3</text><text x="59" y="16">2</text><text x="16" y="37">6</text><text x="37" y="37">1</text><text x="59" y="37">9</text><text x="16" y="59">8</text><text x="37" y="59">9</text><text x="59" y="59">8</text>
                </g>
                <g fill="currentColor" font-family="monospace" font-size="5" text-anchor="middle" dominant-baseline="middle" opacity="0.4">
                    <text x="81" y="16">1</text><text x="81" y="37">3</text><text x="16" y="81">1</text><text x="37" y="81">3</text><text x="59" y="81">1</text><text x="81" y="81">1</text>
                </g>
            </svg>
            @break
        @case('minesweeper')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                <g stroke="currentColor" stroke-width="1" fill="none" opacity="0.5">
                    <line x1="0" y1="25" x2="100" y2="25" /><line x1="0" y1="50" x2="100" y2="50" /><line x1="0" y1="75" x2="100" y2="75" />
                    <line x1="25" y1="0" x2="25" y2="100" /><line x1="50" y1="0" x2="50" y2="100" /><line x1="75" y1="0" x2="75" y2="100" />
                </g>
                @for($y = 0; $y < 4; $y++) @for($x = 0; $x < 4; $x++)
                <rect x="{{ 2 + $x * 25 }}" y="{{ 2 + $y * 25 }}" width="21" height="21" fill="hsl(var(--surface) / .1)" opacity="0.8" />
                @endfor @endfor
                <g fill="hsl(210 100% 50%)" font-family="monospace" font-size="7" text-anchor="middle" dominant-baseline="middle" font-weight="bold">
                    <text x="12.5" y="12.5">1</text><text x="37.5" y="12.5">2</text><text x="62.5" y="12.5">1</text><text x="87.5" y="12.5">1</text>
                    <text x="12.5" y="37.5">2</text><text x="37.5" y="37.5">3</text><text x="62.5" y="37.5">2</text><text x="87.5" y="37.5">1</text>
                    <text x="12.5" y="62.5">1</text><text x="37.5" y="62.5">2</text><text x="62.5" y="62.5">1</text><text x="87.5" y="62.5">1</text>
                </g>
                <g fill="hsl(var(--game-red))" transform="translate(87.5, 37.5)"><polygon points="0,-7.5 0,-15 10,-10" /><rect x="-1" y="-7.5" width="1" height="10"/></g>
                <g fill="hsl(var(--game-red))" transform="translate(87.5, 87.5)"><circle cx="0" cy="0" r="6" /><g stroke="hsl(var(--game-red))" stroke-width="1.5"><line x1="-4" y1="-4" x2="4" y2="4" /><line x1="4" y1="-4" x2="-4" y2="4" /><line x1="0" y1="-4" x2="0" y2="4" /><line x1="-4" y1="0" x2="4" y2="0" /></g></g>
            </svg>
            @break
        @case('snake')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                <path d="M15 50 Q25 30 35 50 Q45 70 55 50 Q65 30 75 50 Q85 70 90 50" stroke="hsl(var(--constellation))" stroke-width="3" fill="none" opacity="0.8" />
                @foreach([[15,50],[35,50],[55,50],[75,50]] as $p)<circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3" fill="hsl(var(--constellation))" opacity="0.9" />@endforeach
                <g fill="hsl(var(--star))"><circle cx="90" cy="50" r="4" /><circle cx="92" cy="47" r="1" fill="hsl(var(--ink))" /><circle cx="92" cy="53" r="1" fill="hsl(var(--ink))" /></g>
                <g fill="hsl(var(--game-red))"><circle cx="25" cy="25" r="3" /><circle cx="85" cy="75" r="3" /><rect x="24" y="22" width="1" height="3" fill="hsl(var(--ink))" /><rect x="84" y="72" width="1" height="3" fill="hsl(var(--ink))" /></g>
            </svg>
            @break
        @case('2048')
            <svg width="100" height="100" viewBox="0 0 100 100" class="{{ $class }}" aria-hidden="true">
                <g stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.6">
                    <rect x="8" y="8" width="42" height="42" rx="3"/><rect x="50" y="8" width="42" height="42" rx="3"/>
                    <rect x="8" y="50" width="42" height="42" rx="3"/><rect x="50" y="50" width="42" height="42" rx="3"/>
                </g>
                <rect x="10" y="10" width="38" height="38" rx="2" fill="hsl(var(--surface) / .15)" />
                <text x="29" y="29" fill="hsl(var(--ink-muted))" font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle" font-weight="bold">2</text>
                <rect x="10" y="52" width="38" height="38" rx="2" fill="hsl(var(--surface) / .25)" />
                <text x="29" y="71" fill="hsl(var(--ink-muted))" font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle" font-weight="bold">4</text>
                <rect x="52" y="52" width="38" height="38" rx="2" fill="hsl(var(--star) / .4)" />
                <text x="71" y="71" fill="hsl(var(--ink))" font-family="monospace" font-size="8" text-anchor="middle" dominant-baseline="middle" font-weight="bold">8</text>
            </svg>
            @break
        @case('board')
            <x-heroicon-o-rectangle-group class="w-20 h-20 text-ink/70" />
            @break
        @default
            <x-heroicon-o-sparkles class="w-20 h-20 text-ink/70" />
    @endswitch
</div>
