<div class="section py-12 md:py-16" x-data="{ showRules: false }" x-init="
    $wire.on('ai-move-delay', () => {
        setTimeout(() => {
            $wire.makeAiMove();
        }, 800); // 800ms delay to let player move animation complete
    });
">
    <div class="max-w-2xl mx-auto space-y-6 sm:space-y-8" x-data="{ aiThinking: false, thinkingDots: '' }" x-init="
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
            }, 800 + Math.random() * 400); // 800-1200ms delay
        });
    ">
        {{-- Back nav --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">Tic-Tac-Toe</h1>
        </div>

        {{-- Rules (toggleable) --}}
        <details x-data="{ open: false }" @toggle="open = $el.open" class="glass rounded-xl border border-[hsl(var(--border)/.1)] overflow-hidden">
            <summary class="px-6 py-3 cursor-pointer text-ink/80 hover:text-ink hover:bg-[hsl(var(--surface)/.08)] transition-colors list-none flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span>How to Play</span>
                    <div class="flex items-center gap-1">
                        <div class="piece-moon w-4 h-4 opacity-60" aria-hidden="true"></div>
                        <div class="piece-star w-4 h-4 opacity-60" aria-hidden="true"></div>
                    </div>
                </div>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" ::class="{ 'rotate-180': open }" />
            </summary>
            <div class="px-6 py-4 border-t border-[hsl(var(--border)/.1)] space-y-3 text-ink/70 text-sm">
                <div class="space-y-2">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-star/20 flex items-center justify-center text-xs font-semibold text-star mt-0.5">1</div>
                        <p>Get three pieces in a row to win — horizontally, vertically, or diagonally.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-star/20 flex items-center justify-center text-xs font-semibold text-star mt-0.5">2</div>
                        <p>Choose your symbol: Moon (🌙) or Star (⭐) when playing against AI.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-star/20 flex items-center justify-center text-xs font-semibold text-star mt-0.5">3</div>
                        <p>Challenge the AI at three difficulty levels or play with a friend locally.</p>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-[hsl(var(--surface)/.05)] rounded-lg border border-[hsl(var(--border)/.2)]">
                    <p class="text-xs text-ink/60 font-medium mb-2">Difficulty Levels:</p>
                    <div class="grid grid-cols-1 gap-1 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-12 text-ink/80">Easy:</span>
                            <span class="text-ink/60">Makes obvious moves, perfect for beginners</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-12 text-ink/80">Medium:</span>
                            <span class="text-ink/60">Strategic but makes occasional mistakes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-12 text-ink/80">Impossible:</span>
                            <span class="text-ink/60">Perfect play using advanced algorithms</span>
                        </div>
                    </div>
                </div>
            </div>
        </details>

        {{-- Game Mode Selection --}}
        @if($movesCount === 0)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-4 sm:p-6 space-y-4 sm:space-y-5" x-data="{ selectedMode: '{{ $gameMode }}', selectedSymbol: '{{ $playerSymbol }}' }">
                <div class="text-center">
                    <h3 class="text-lg sm:text-xl font-semibold text-ink mb-2">Choose Your Challenge</h3>
                    <p class="text-sm text-ink/70">Select your preferred game mode and symbol</p>
                </div>

                {{-- Game Mode Cards --}}
                <div class="space-y-3 sm:space-y-4">
                    {{-- Pass & Play --}}
                    <button wire:click="setGameMode('pvp')"
                            class="w-full p-3 sm:p-4 rounded-xl border-2 transition-all duration-200 {{ $gameMode === 'pvp' ? 'bg-star/10 border-star text-star shadow-lg shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:bg-[hsl(var(--surface)/.1)] hover:shadow-md' }}"
                            x-on:click="selectedMode = 'pvp'">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <div class="piece-moon w-5 sm:w-6 h-5 sm:h-6" aria-hidden="true"></div>
                                <div class="piece-star w-5 sm:w-6 h-5 sm:h-6" aria-hidden="true"></div>
                            </div>
                            <div class="text-left flex-1">
                                <div class="font-semibold">Pass & Play</div>
                                <div class="text-xs sm:text-sm opacity-75">Local multiplayer</div>
                            </div>
                            <div x-show="$wire.gameMode === 'pvp'" x-transition class="ml-auto">
                                <x-heroicon-o-check class="w-4 sm:w-5 h-4 sm:h-5 text-star" />
                            </div>
                        </div>
                    </button>

                    {{-- AI Modes --}}
                    <div class="space-y-2 sm:space-y-3">
                        <p class="text-sm sm:text-base font-medium text-ink/80 text-center">Single Player</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
                            <button wire:click="setGameMode('ai-easy', 'X')"
                                    class="p-3 sm:p-4 rounded-lg sm:rounded-xl border transition-all duration-200 {{ $gameMode === 'ai-easy' ? 'bg-star/10 border-star text-star shadow-md shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:shadow-sm' }}"
                                    x-on:click="selectedMode = 'ai-easy'">
                                <div class="text-center">
                                    <div class="piece-moon w-5 sm:w-6 h-5 sm:h-6 mx-auto mb-1 sm:mb-2" aria-hidden="true"></div>
                                    <div class="text-sm sm:text-base font-semibold">Easy</div>
                                    <div class="text-xs opacity-75">Beginner friendly</div>
                                </div>
                            </button>
                            <button wire:click="setGameMode('ai-medium', 'X')"
                                    class="p-3 sm:p-4 rounded-lg sm:rounded-xl border transition-all duration-200 {{ $gameMode === 'ai-medium' ? 'bg-star/10 border-star text-star shadow-md shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:shadow-sm' }}"
                                    x-on:click="selectedMode = 'ai-medium'">
                                <div class="text-center">
                                    <div class="piece-moon w-5 sm:w-6 h-5 sm:h-6 mx-auto mb-1 sm:mb-2" aria-hidden="true"></div>
                                    <div class="text-sm sm:text-base font-semibold">Medium</div>
                                    <div class="text-xs opacity-75">Strategic play</div>
                                </div>
                            </button>
                            <button wire:click="setGameMode('ai-impossible', 'X')"
                                    class="p-3 sm:p-4 rounded-lg sm:rounded-xl border transition-all duration-200 {{ $gameMode === 'ai-impossible' ? 'bg-star/10 border-star text-star shadow-md shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:shadow-sm' }}"
                                    x-on:click="selectedMode = 'ai-impossible'">
                                <div class="text-center">
                                    <div class="piece-moon w-5 sm:w-6 h-5 sm:h-6 mx-auto mb-1 sm:mb-2" aria-hidden="true"></div>
                                    <div class="text-sm sm:text-base font-semibold">Impossible</div>
                                    <div class="text-xs opacity-75">Perfect play</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Symbol Selection for AI modes --}}
                <div x-show="selectedMode.startsWith('ai-')" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="text-center space-y-3">
                        <p class="text-sm sm:text-base font-medium text-ink/80">Choose Your Symbol</p>
                        <div class="flex justify-center gap-3 sm:gap-4">
                            <button wire:click="setGameMode('{{ $gameMode }}', 'X')"
                                    class="flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl border transition-all duration-200 {{ $playerSymbol === 'X' ? 'bg-star/10 border-star text-star shadow-md shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:shadow-sm' }}"
                                    x-on:click="selectedSymbol = 'X'">
                                <div class="piece-moon w-5 sm:w-6 h-5 sm:h-6" aria-hidden="true"></div>
                                <span class="font-medium text-sm sm:text-base">Moon</span>
                            </button>
                            <button wire:click="setGameMode('{{ $gameMode }}', 'O')"
                                    class="flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl border transition-all duration-200 {{ $playerSymbol === 'O' ? 'bg-star/10 border-star text-star shadow-md shadow-star/20' : 'bg-[hsl(var(--surface)/.05)] border-[hsl(var(--border)/.3)] text-ink hover:border-star/50 hover:shadow-sm' }}"
                                    x-on:click="selectedSymbol = 'O'">
                                <div class="piece-star w-5 sm:w-6 h-5 sm:h-6" aria-hidden="true"></div>
                                <span class="font-medium text-sm sm:text-base">Star</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Game Status --}}
        @if($winner || $isDraw)
            <div class="glass rounded-xl border p-6 text-center {{ $winner ? 'border-star/40 bg-star/5' : 'border-constellation/40 bg-constellation/5' }}">
                @if($winner)
                    <div class="space-y-3">
                        <div class="flex items-center justify-center gap-3">
                            @if($winner === 'X')
                                <div class="piece-moon text-4xl" aria-hidden="true"></div>
                            @else
                                <div class="piece-star text-4xl" aria-hidden="true"></div>
                            @endif
                            <p class="text-xl font-semibold {{ $winner === 'X' ? 'text-ink' : 'text-star' }}">
                                {{ $winner === 'X' ? 'Moon' : 'Star' }} wins!
                            </p>
                            @if($winner === 'X')
                                <div class="piece-moon text-4xl" aria-hidden="true"></div>
                            @else
                                <div class="piece-star text-4xl" aria-hidden="true"></div>
                            @endif
                        </div>
                        <div class="flex items-center justify-center gap-4 text-sm text-ink/70">
                            <span>{{ $movesCount }} moves</span>
                            <span class="w-1 h-1 bg-ink/30 rounded-full"></span>
                            <span>{{ round($movesCount / 2, 1) }}s</span>
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        <div class="flex items-center justify-center gap-3">
                            <div class="piece-moon text-4xl opacity-75" aria-hidden="true"></div>
                            <p class="text-xl font-semibold text-constellation">It's a draw!</p>
                            <div class="piece-star text-4xl opacity-75" aria-hidden="true"></div>
                        </div>
                        <div class="flex items-center justify-center gap-4 text-sm text-ink/70">
                            <span>{{ $movesCount }} moves</span>
                            <span class="w-1 h-1 bg-ink/30 rounded-full"></span>
                            <span>{{ round($movesCount / 2, 1) }}s</span>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center space-y-2">
                <p class="text-ink text-base">
                    @if($gameMode === 'pvp')
                        Current turn:
                        @if($currentPlayer === 'X')
                            <strong class="text-ink inline-flex items-center gap-2">
                                <div class="piece-moon w-5 h-5" aria-hidden="true"></div>
                                Moon
                            </strong>
                        @else
                            <strong class="text-star inline-flex items-center gap-2">
                                <div class="piece-star w-5 h-5" aria-hidden="true"></div>
                                Star
                            </strong>
                        @endif
                    @else
                        @if($currentPlayer === $playerSymbol)
                            <span class="inline-flex items-center gap-2">
                                Your turn:
                                @if($playerSymbol === 'X')
                                    <div class="piece-moon w-5 h-5" aria-hidden="true"></div>
                                    <strong class="text-ink">Moon</strong>
                                @else
                                    <div class="piece-star w-5 h-5" aria-hidden="true"></div>
                                    <strong class="text-star">Star</strong>
                                @endif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 text-ink/70">
                                <span class="flex items-center gap-1">
                                    AI thinking
                                    <span x-text="thinkingDots" class="inline-block min-w-[12px]"></span>
                                </span>
                                @if($currentPlayer === 'X')
                                    <div class="piece-moon w-5 h-5 animate-pulse" aria-hidden="true"></div>
                                @else
                                    <div class="piece-star w-5 h-5 animate-pulse" aria-hidden="true"></div>
                                @endif
                            </span>
                        @endif
                    @endif
                </p>
                @if($gameMode !== 'pvp')
                    <p class="text-sm text-ink/60">
                        Difficulty: <span class="capitalize">{{ $aiDifficulty }}</span>
                    </p>
                @endif
            </div>
        @endif

        {{-- Game Board --}}
        <div class="grid grid-cols-3 gap-2 sm:gap-3 max-w-sm sm:max-w-lg mx-auto aspect-square p-3 sm:p-4 bg-[hsl(var(--space-800)/.3)] rounded-xl sm:rounded-2xl border border-[hsl(var(--border)/.2)] shadow-lg relative">
            {{-- Victory Line Overlay --}}
            @if($winner && !empty($winningPositions))
                <div class="absolute inset-3 sm:inset-4 pointer-events-none z-20">
                    @if($winningPositions === [0, 1, 2]) {{-- Top row --}}
                        <div class="victory-line absolute top-0 left-0 right-0 h-0.5" style="top: 16.66%; transform: translateY(-50%);"></div>
                    @elseif($winningPositions === [3, 4, 5]) {{-- Middle row --}}
                        <div class="victory-line absolute top-0 left-0 right-0 h-0.5" style="top: 50%; transform: translateY(-50%);"></div>
                    @elseif($winningPositions === [6, 7, 8]) {{-- Bottom row --}}
                        <div class="victory-line absolute bottom-0 left-0 right-0 h-0.5" style="bottom: 16.66%; transform: translateY(50%);"></div>
                    @elseif($winningPositions === [0, 3, 6]) {{-- Left column --}}
                        <div class="victory-line absolute top-0 bottom-0 left-0 w-0.5" style="left: 16.66%; transform: translateX(-50%);"></div>
                    @elseif($winningPositions === [1, 4, 7]) {{-- Middle column --}}
                        <div class="victory-line absolute top-0 bottom-0 left-0 right-0 w-0.5" style="left: 50%; transform: translateX(-50%);"></div>
                    @elseif($winningPositions === [2, 5, 8]) {{-- Right column --}}
                        <div class="victory-line absolute top-0 bottom-0 right-0 w-0.5" style="right: 16.66%; transform: translateX(50%);"></div>
                    @elseif($winningPositions === [0, 4, 8]) {{-- Main diagonal --}}
                        <div class="victory-line absolute top-0 left-0 bottom-0 right-0" style="transform: rotate(45deg) translate(50%, -50%); width: 141.42%; height: 0.5px; left: -20.71%; top: 50%;"></div>
                    @elseif($winningPositions === [2, 4, 6]) {{-- Anti-diagonal --}}
                        <div class="victory-line absolute top-0 left-0 bottom-0 right-0" style="transform: rotate(-45deg) translate(-50%, -50%); width: 141.42%; height: 0.5px; left: -20.71%; top: 50%;"></div>
                    @endif
                </div>
            @endif

            @foreach($board as $index => $cell)
                <button
                    wire:click="makeMove({{ $index }})"
                    class="bg-[hsl(var(--surface)/.08)] border-2 border-[hsl(var(--border)/.4)] rounded-lg sm:rounded-xl flex items-center justify-center transition-all duration-200 hover:border-star hover:bg-[hsl(var(--surface)/.15)] hover:-translate-y-1 hover:shadow-lg hover:shadow-[hsl(var(--star)/.1)] active:scale-95 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:border-[hsl(var(--border)/.4)] disabled:hover:bg-[hsl(var(--surface)/.05)] focus:outline-none focus:ring-2 focus:ring-star/50 focus:ring-offset-2 focus:ring-offset-transparent relative overflow-hidden {{ in_array($index, $winningPositions) ? 'ring-2 ring-star/60 ring-offset-1 ring-offset-transparent' : '' }}"
                    @disabled($cell !== null || $winner !== null || $isDraw)
                    aria-label="Cell {{ $index + 1 }}{{ $cell ? ', ' . $cell : ', empty' }}"
                    style="min-height: 60px;"
                    x-data="{ isHovered: false }"
                    @mouseenter="isHovered = true"
                    @mouseleave="isHovered = false"
                >
                    {{-- Hover glow effect --}}
                    <div
                        x-show="isHovered && {{ $cell ? 'false' : 'true' }} && {{ $winner ? 'false' : 'true' }} && {{ $isDraw ? 'false' : 'true' }}"
                        x-transition:enter="transition-opacity duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="absolute inset-0 bg-gradient-to-br from-star/10 to-constellation/10 rounded-lg sm:rounded-xl"
                    ></div>

                    @if($cell === 'X')
                        <div class="piece-moon {{ in_array($index, $winningPositions) ? 'winning' : '' }} text-4xl sm:text-6xl relative z-10" aria-label="Moon"></div>
                    @elseif($cell === 'O')
                        <div class="piece-star {{ in_array($index, $winningPositions) ? 'winning' : '' }} text-4xl sm:text-6xl relative z-10" aria-label="Star"></div>
                    @endif

                    {{-- Position indicator for empty cells --}}
                    @if(!$cell)
                        <div class="text-xs text-ink/30 font-mono absolute bottom-1 right-1">{{ $index + 1 }}</div>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Controls --}}
        <div class="flex justify-center gap-4">
            <button wire:click="newGame"
                    class="px-8 py-3 rounded-xl bg-star text-space-900 font-semibold hover:-translate-y-1 hover:shadow-lg hover:shadow-star/20 transition-all flex items-center gap-2 disabled:opacity-50 disabled:hover:translate-y-0"
                    @disabled($movesCount === 0 && $gameMode === 'pvp')>
                <x-heroicon-o-arrow-path class="w-5 h-5" />
                New Game
            </button>
        </div>

        {{-- Stats --}}
        <div class="text-center space-y-2">
            <div class="flex items-center justify-center gap-6 text-sm text-ink/60">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-cursor-arrow-rays class="w-4 h-4" />
                    <span>{{ $movesCount }} moves</span>
                </div>
                @if($movesCount > 0)
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-clock class="w-4 h-4" />
                        <span>{{ round($movesCount / 2, 1) }}s</span>
                    </div>
                @endif
                @if($gameMode !== 'pvp' && $movesCount > 0)
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-cpu-chip class="w-4 h-4" />
                        <span class="capitalize">{{ $aiDifficulty }}</span>
                    </div>
                @endif
            </div>

            {{-- Game Progress Indicator --}}
            @if($movesCount > 0 && !$winner && !$isDraw)
                <div class="flex justify-center gap-1 mt-2">
                    @for($i = 0; $i < 9; $i++)
                        <div class="w-2 h-2 rounded-full {{ $board[$i] !== null ? 'bg-star/60' : 'bg-[hsl(var(--border)/.3)]' }} transition-colors"></div>
                    @endfor
                </div>
            @endif
        </div>
    </div>
</div>

