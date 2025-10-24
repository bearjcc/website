<div class="section py-12 md:py-16">
    <div class="max-w-2xl mx-auto space-y-8">
        {{-- Back nav --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">Connect 4</h1>
        </div>

        {{-- Rules --}}
        <details class="glass rounded-xl border border-[hsl(var(--border)/.1)] overflow-hidden">
            <summary class="px-6 py-3 cursor-pointer text-ink/80 hover:text-ink hover:bg-[hsl(var(--surface)/.08)] transition-colors list-none flex items-center justify-between">
                <span>Rules</span>
                <x-heroicon-o-chevron-down class="w-5 h-5" />
            </summary>
            <div class="px-6 py-4 border-t border-[hsl(var(--border)/.1)] space-y-2 text-ink/70 text-sm">
                @foreach((new \App\Games\Connect4\Connect4Game())->rules() as $rule)
                    <p>{{ $rule }}</p>
                @endforeach
            </div>
        </details>

        {{-- Game Status --}}
        @if($gameStarted)
            <div class="glass rounded-xl border border-[hsl(var(--border)/.1)] p-4">
                <div class="flex items-center justify-center gap-4 text-sm">
                    <span>Turn: <strong class="text-ink">{{ ucfirst($state['currentPlayer']) }}</strong></span>
                    <span>Moves: <strong class="text-ink">{{ $moveCount }}</strong></span>
                    <span>Time:
                        @php
                            $elapsed = $this->getElapsedTime();
                            $minutes = floor($elapsed / 60);
                            $seconds = $elapsed % 60;
                            echo sprintf('%d:%02d', $minutes, $seconds);
                        @endphp
                    </span>
                </div>
            </div>
        @endif

        @if($state['gameOver'])
            <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
                <div class="flex items-center justify-center gap-2">
                    @if($state['winner'] === 'draw')
                        <x-heroicon-o-scale class="w-5 h-5 text-ink" />
                        <p class="text-lg font-semibold text-star">Draw Game</p>
                        <x-heroicon-o-scale class="w-5 h-5 text-ink" />
                    @else
                        <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                        <p class="text-lg font-semibold text-star">{{ ucfirst($state['winner']) }} wins!</p>
                        <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
                    @endif
                </div>
                <div class="space-y-2 text-sm text-ink/70">
                    <div class="flex items-center justify-center gap-6">
                        <span>Moves: {{ $moveCount }}</span>
                        <span>Time:
                            @php
                                $elapsed = $this->getElapsedTime();
                                $minutes = floor($elapsed / 60);
                                $seconds = $elapsed % 60;
                                echo sprintf('%d:%02d', $minutes, $seconds);
                            @endphp
                        </span>
                    </div>
                    @if(isset($state['score']))
                        <div class="flex items-center justify-center gap-4">
                            @foreach(['red', 'yellow'] as $player)
                                @if(isset($state['score'][$player]))
                                    <span>{{ ucfirst($player) }}: {{ $state['score'][$player] }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

    <!-- Game Board -->
    <div class="board-container">
        <div class="game-board">
            @for($row = 0; $row < 6; $row++)
                @for($col = 0; $col < 7; $col++)
                    @php
                        $piece = $state['board'][$row][$col];
                        $isWinning = $this->isWinningPiece($row, $col);
                    @endphp
                    <div 
                        class="cell"
                        wire:click="dropPiece({{ $col }})"
                        @class([
                            'clickable' => !$state['gameOver'] && $this->canDropInColumn($col),
                            'column-' . $col
                        ])
                    >
                        @if($piece)
                            <div class="piece piece-{{ $piece }} {{ $isWinning ? 'winning' : '' }}"></div>
                        @else
                            <div class="empty-slot"></div>
                        @endif
                    </div>
                @endfor
            @endfor
        </div>
        
        <!-- Column indicators for mobile/accessibility -->
        <div class="column-indicators">
            @for($col = 0; $col < 7; $col++)
                <button 
                    wire:click="dropPiece({{ $col }})"
                    @disabled($state['gameOver'] || !$this->canDropInColumn($col))
                    class="column-button {{ $this->canDropInColumn($col) && !$state['gameOver'] ? 'available' : '' }}"
                >
                    ↓
                </button>
            @endfor
        </div>
    </div>

        {{-- Game Controls --}}
        <div class="game-controls">
            <button wire:click="newGame"
                    class="control-btn new-game"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    Livewire.on('game-completed', (event) => {
        // Add celebration effect
        const gameContainer = document.querySelector('.section');
        if (gameContainer) {
            gameContainer.classList.add('celebration');
            setTimeout(() => {
                gameContainer.classList.remove('celebration');
            }, 2000);
        }
    });
});
</script>
@endpush

