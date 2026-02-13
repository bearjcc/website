<x-ui.game-wrapper
    title="Checkers"
    :rules="[
        'Move pieces diagonally to empty dark squares only',
        'Regular pieces move forward toward the opponent\'s side',
        'Jump over opponent pieces diagonally to capture them',
        'Multiple consecutive jumps allowed in a single turn',
        'Crown pieces as kings when reaching opponent\'s back row',
        'Kings can move and jump in any diagonal direction',
        'Capture all opponent pieces or block them to win'
    ]"
    x-data="{ showCelebration: false, celebrationTimer: null }"
    x-init="
        $wire.on('game-completed', (event) => {
            showCelebration = true;
            clearTimeout(celebrationTimer);
            celebrationTimer = setTimeout(() => {
                showCelebration = false;
            }, 3000);
        });
    ">

    {{-- Game Instructions --}}
    <div class="text-center mb-6">
        <p class="text-sm text-ink/60 mb-2">Click a piece to select, then click destination to move</p>
        <p class="text-xs text-ink/40">Jump over opponents to capture their pieces!</p>
    </div>

    {{-- Game Status and Score --}}
    @if($gameStarted)
        <div class="flex flex-wrap justify-center gap-4 text-sm">
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Turn:</span>
                <strong class="text-{{ $currentPlayer === 'red' ? 'star' : 'constellation' }} ml-2 font-bold">
                    {{ ucfirst($currentPlayer) }}
                </strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Moves:</span>
                <strong class="text-ink ml-2 font-bold">{{ $moves }}</strong>
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Captured:</span>
                <strong class="text-ink ml-2 font-bold">{{ $capturedPieces['red'] + $capturedPieces['black'] }}</strong>
            </div>
        </div>
    @endif

    {{-- Game Status Messages --}}
    @if($gameOver)
        <div class="glass rounded-xl border border-star/40 bg-star/5 p-6 text-center space-y-3">
            @if($winner === 'draw')
                <div class="flex items-center justify-center gap-2">
                    <x-heroicon-o-scale class="w-5 h-5 text-ink" />
                    <p class="text-lg font-semibold text-star">Stalemate!</p>
                    <x-heroicon-o-scale class="w-5 h-5 text-ink" />
                </div>
                <p class="text-sm text-ink/70">No more moves possible - it's a draw!</p>
            @else
                <div class="flex items-center justify-center gap-2">
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                    <p class="text-lg font-semibold text-star">{{ ucfirst($winner) }} wins!</p>
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
                </div>
                <div class="space-y-1 text-sm text-ink/70">
                    <div>Moves: <strong class="text-ink font-bold">{{ $moves }}</strong></div>
                    <div>Pieces Captured: <strong class="text-ink font-bold">{{ $capturedPieces['red'] + $capturedPieces['black'] }}</strong></div>
                </div>
            @endif
        </div>
    @endif

    {{-- Game Board --}}
    <div class="flex justify-center">
        <div class="checkers-board">
            @for($row = 0; $row < 8; $row++)
                @for($col = 0; $col < 8; $col++)
                    @php
                        $piece = $this->getPieceAt($row, $col);
                        $isDarkSquare = $this->isDarkSquare($row, $col);
                        $isLightSquare = $this->isLightSquare($row, $col);
                        $isSelected = $selectedSquare && $selectedSquare['row'] === $row && $selectedSquare['col'] === $col;
                        $isValidMove = $this->isValidMoveDestination($row, $col);
                        $isKing = $piece && $this->isKing($piece);
                    @endphp

                    <div class="checkers-cell {{ $isDarkSquare ? 'dark' : 'light' }} {{ $isSelected ? 'selected' : '' }} {{ $isValidMove ? 'valid-move' : '' }}"
                         wire:click="selectSquare({{ $row }}, {{ $col }})"
                         tabindex="0"
                         aria-label="Square {{ $row + 1 }}, {{ $col + 1 }}{{ $piece ? ', ' . $this->getPieceDisplayName($piece) : ', empty' }}">

                        @if($piece)
                            <div class="checkers-piece {{ $piece }} {{ $isKing ? 'king' : '' }}"
                                 aria-label="{{ $this->getPieceDisplayName($piece) }}">
                                @if($isKing)
                                    <div class="king-crown" aria-hidden="true">K</div>
                                @endif
                            </div>
                        @elseif($isValidMove)
                            <div class="valid-move-indicator"></div>
                        @endif
                    </div>
                @endfor
            @endfor
        </div>
    </div>

    {{-- Game Controls --}}
    <div class="space-y-4">
        <div class="flex flex-wrap justify-center gap-2">
            <button wire:click="newGame"
                    class="px-6 py-2 rounded-lg bg-star text-space-900 font-semibold hover:-translate-y-0.5 hover:shadow-lg hover:shadow-star/20 transition-all inline-flex items-center gap-2"
                    aria-label="Start new game">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                <span>New Game</span>
            </button>
        </div>

        {{-- Legend --}}
        <div class="flex justify-center">
            <div class="flex items-center gap-6 text-xs text-ink/60">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-star/80"></div>
                    <span>Red</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-constellation/80"></div>
                    <span>Black</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-star border border-star/60 flex items-center justify-center text-[8px] font-bold text-space-900">K</div>
                    <span>King</span>
                </div>
            </div>
        </div>
    </div>
</x-ui.game-wrapper>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    Livewire.on('game-completed', (event) => {
        // Add celebration effect to the game wrapper
        const gameSection = document.querySelector('.section');
        if (gameSection) {
            gameSection.classList.add('celebration');
            setTimeout(() => {
                gameSection.classList.remove('celebration');
            }, 2000);
        }
    });
});
</script>
@endpush

