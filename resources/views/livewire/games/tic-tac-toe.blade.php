<div class="section py-12 md:py-16" x-data="{ showRules: false }">
    <div class="max-w-2xl mx-auto space-y-8">
        {{-- Back nav --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('games.index') }}" 
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
                <span>Rules</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 transition-transform" ::class="{ 'rotate-180': open }" />
            </summary>
            <div class="px-6 py-4 border-t border-[hsl(var(--border)/.1)] space-y-2 text-ink/70 text-sm">
                <p>Get three in a row (horizontal, vertical, or diagonal) to win.</p>
                <p>Play against a friend or challenge the AI at three difficulty levels.</p>
            </div>
        </details>

        {{-- Game Mode Selection --}}
        @if($movesCount === 0)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-6 space-y-4">
                <h3 class="text-lg font-semibold text-ink">Mode</h3>
                
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setGameMode('pvp')" 
                            class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'pvp' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                        Pass & Play
                    </button>
                    
                    <button wire:click="setGameMode('ai-easy', 'X')" 
                            class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-easy' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                        Easy AI
                    </button>
                    <button wire:click="setGameMode('ai-medium', 'X')" 
                            class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-medium' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                        Medium AI
                    </button>
                    <button wire:click="setGameMode('ai-hard', 'X')" 
                            class="px-4 py-2 rounded-lg border transition-all {{ $gameMode === 'ai-hard' ? 'bg-star text-space-900 border-star' : 'bg-[hsl(var(--surface)/.1)] text-ink border-[hsl(var(--border)/.3)] hover:border-star' }}">
                        Hard AI
                    </button>
                </div>
            </div>
        @endif

        {{-- Game Status --}}
        @if($winner || $isDraw)
            <div class="glass rounded-xl border p-4 text-center {{ $winner ? 'border-star/40 bg-star/5' : 'border-constellation/40 bg-constellation/5' }}">
                @if($winner)
                    <p class="text-lg font-semibold text-star">Player {{ $winner }} wins.</p>
                @else
                    <p class="text-lg font-semibold text-constellation">Draw.</p>
                @endif
            </div>
        @else
            <div class="text-center">
                <p class="text-ink text-sm">
                    Current turn: <strong class="text-star">{{ $currentPlayer }}</strong>
                    @if($gameMode !== 'pvp')
                        <span class="text-ink/60">(You: {{ $playerSymbol }})</span>
                    @endif
                </p>
            </div>
        @endif

        {{-- Game Board --}}
        <div class="grid grid-cols-3 gap-2 max-w-md mx-auto aspect-square">
            @foreach($board as $index => $cell)
                <button 
                    wire:click="makeMove({{ $index }})"
                    class="bg-[hsl(var(--surface)/.05)] border-2 border-[hsl(var(--border)/.3)] rounded-xl flex items-center justify-center text-5xl font-bold transition-all hover:border-star hover:bg-[hsl(var(--surface)/.1)] hover:-translate-y-1 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:border-[hsl(var(--border)/.3)]"
                    @disabled($cell !== null || $winner !== null || $isDraw)
                    aria-label="Cell {{ $index + 1 }}{{ $cell ? ', ' . $cell : ', empty' }}"
                >
                    @if($cell === 'X')
                        <span class="animate-in fade-in zoom-in duration-150" style="color: hsl(0 70% 70%);">X</span>
                    @elseif($cell === 'O')
                        <span class="animate-in fade-in zoom-in duration-150" style="color: hsl(var(--constellation));">O</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Controls --}}
        <div class="flex justify-center gap-4">
            <button wire:click="newGame" 
                    class="px-6 py-3 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-1 hover:shadow-lg hover:shadow-star/20 transition-all">
                New Game
            </button>
        </div>

        {{-- Stats --}}
        <div class="text-center text-sm text-ink/60 space-y-1">
            <p>Moves: {{ $movesCount }}</p>
        </div>
    </div>
</div>

