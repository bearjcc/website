<div x-data="{ showRules: false }" x-init="
    $wire.on('ai-move-delay', () => {
        setTimeout(() => { $wire.makeAiMove(); }, 800);
    });
">
    <div x-data="{ aiThinking: false, thinkingDots: '' }" x-init="
        $wire.on('ai-move-delay', () => {
            aiThinking = true;
            thinkingDots = '';
            let dots = 0;
            const interval = setInterval(() => {
                dots = (dots + 1) % 4;
                thinkingDots = '.'.repeat(dots);
                if (!aiThinking) clearInterval(interval);
            }, 300);
            setTimeout(() => {
                aiThinking = false;
                clearInterval(interval);
                $wire.makeAiMove();
            }, 800 + Math.random() * 400);
        });
    ">
        {{-- Chrome bar: game name + turn (wireframe 3.1) --}}
        <x-ui.game-chrome
            :gameTitle="$game->title"
            :gamePageUrl="route('games.show', $game->slug)"
            :rightContent="$this->getChromeTurnText()"
        />

        {{-- Play main: instruction, board, bubbles, action row, hint (wireframe 3.2) --}}
        <x-ui.play-main>
            <p class="text-center text-ink/80 text-sm">Get three in a row.</p>

            @if($winner || $isDraw)
                <div class="text-center py-2 {{ $winner ? 'text-star' : 'text-constellation' }}">
                    @if($winner)
                        <span class="font-semibold">{{ $winner === 'X' ? 'Moon' : 'Star' }} wins!</span>
                    @else
                        <span class="font-semibold">Draw!</span>
                    @endif
                </div>
            @endif

            {{-- Board --}}
            <div class="grid grid-cols-3 gap-1 sm:gap-2 aspect-square p-3 sm:p-4 bg-[hsl(var(--space-800)/.3)] rounded-xl border-2 border-[hsl(var(--border)/.3)] relative" style="background-image: linear-gradient(hsl(var(--border)/.2) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)/.2) 1px, transparent 1px); background-size: 33.33% 33.33%;">
                @if($winner && !empty($winningPositions))
                    <div class="absolute inset-2 sm:inset-3 pointer-events-none z-20">
                        @if($winningPositions === [0, 1, 2])
                            <div class="victory-line absolute top-0 left-0 right-0 h-0.5" style="top: 16.66%; transform: translateY(-50%);"></div>
                        @elseif($winningPositions === [3, 4, 5])
                            <div class="victory-line absolute top-0 left-0 right-0 h-0.5" style="top: 50%; transform: translateY(-50%);"></div>
                        @elseif($winningPositions === [6, 7, 8])
                            <div class="victory-line absolute bottom-0 left-0 right-0 h-0.5" style="bottom: 16.66%; transform: translateY(50%);"></div>
                        @elseif($winningPositions === [0, 3, 6])
                            <div class="victory-line absolute top-0 bottom-0 left-0 w-0.5" style="left: 16.66%; transform: translateX(-50%);"></div>
                        @elseif($winningPositions === [1, 4, 7])
                            <div class="victory-line absolute top-0 bottom-0 left-0 right-0 w-0.5" style="left: 50%; transform: translateX(-50%);"></div>
                        @elseif($winningPositions === [2, 5, 8])
                            <div class="victory-line absolute top-0 bottom-0 right-0 w-0.5" style="right: 16.66%; transform: translateX(50%);"></div>
                        @elseif($winningPositions === [0, 4, 8])
                            <div class="victory-line absolute top-0 left-0 bottom-0 right-0" style="transform: rotate(45deg) translate(50%, -50%); width: 141.42%; height: 0.5px; left: -20.71%; top: 50%;"></div>
                        @elseif($winningPositions === [2, 4, 6])
                            <div class="victory-line absolute top-0 left-0 bottom-0 right-0" style="transform: rotate(-45deg) translate(-50%, -50%); width: 141.42%; height: 0.5px; left: -20.71%; top: 50%;"></div>
                        @endif
                    </div>
                @endif
                @foreach($board as $index => $cell)
                    <button
                        wire:click="makeMove({{ $index }})"
                        class="bg-transparent flex items-center justify-center transition-all duration-200 hover:bg-[hsl(var(--surface)/.1)] hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:bg-transparent focus:outline-none focus:ring-2 focus:ring-star/50 focus:ring-offset-2 focus:ring-offset-transparent relative overflow-hidden {{ in_array($index, $winningPositions) ? 'ring-2 ring-star/60 ring-offset-1' : '' }}"
                        @disabled($cell !== null || $winner !== null || $isDraw)
                        aria-label="Cell {{ $index + 1 }}{{ $cell ? ', ' . $cell : ', empty' }}"
                        style="min-height: 48px;"
                    >
                        @if($cell === 'X')
                            <div class="piece-moon {{ in_array($index, $winningPositions) ? 'winning' : '' }} text-2xl sm:text-4xl relative z-10" aria-hidden="true"></div>
                        @elseif($cell === 'O')
                            <div class="piece-star {{ in_array($index, $winningPositions) ? 'winning' : '' }} text-2xl sm:text-4xl relative z-10" aria-hidden="true"></div>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- Info bubbles (wireframe 3.3) --}}
            @php
                $bubbles = [
                    ['label' => 'X (you)', 'value' => (string) $xWins, 'highlight' => $playerSymbol === 'X' && !$winner && !$isDraw],
                    ['label' => 'Ties', 'value' => (string) $ties, 'highlight' => $isDraw],
                    ['label' => $gameMode === 'pvp' ? 'O (P2)' : 'O (CPU)', 'value' => (string) $oWins, 'highlight' => $playerSymbol === 'O' && !$winner && !$isDraw],
                ];
            @endphp
            <x-ui.info-bubbles :bubbles="$bubbles" />

            {{-- Action row (wireframe 3.4) --}}
            <x-ui.action-row newGameAction="newGame" />

            {{-- Controls hint (wireframe 3.5) --}}
            <p class="text-center text-xs text-ink/50 pt-1">Tap a cell to play.</p>
        </x-ui.play-main>
    </div>
</div>
