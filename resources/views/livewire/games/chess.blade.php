<x-ui.game-wrapper
    title="Chess"
    :rules="[
        'Move pieces according to standard chess rules',
        'Capture opponent pieces by moving to their square',
        'Protect your king - checkmate ends the game',
        'Pawns promote when reaching the opposite end',
        'Special moves: castling and en passant available',
        'White moves first, then players alternate turns'
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
        <p class="text-xs text-ink/40">Checkmate your opponent to win the game!</p>
    </div>

    {{-- Game Status and Score --}}
    @if($gameStarted)
        <div class="flex flex-wrap justify-center gap-4 text-sm">
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Turn:</span>
                <strong class="text-{{ $currentPlayer === 'white' ? 'ink' : 'constellation' }} ml-2 font-bold">
                    {{ ucfirst($currentPlayer) }}
                </strong>
                @if($inCheck)
                    <span class="ml-2 text-red-400">⚠</span>
                @endif
            </div>
            <div class="px-4 py-2 glass rounded-lg border border-[hsl(var(--border)/.1)]">
                <span class="text-ink/60">Moves:</span>
                <strong class="text-ink ml-2 font-bold">{{ $moves }}</strong>
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
                <p class="text-sm text-ink/70">No legal moves available - it's a draw!</p>
            @else
                <div class="flex items-center justify-center gap-2">
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" />
                    <p class="text-lg font-semibold text-star">{{ ucfirst($winner) }} wins!</p>
                    <x-heroicon-o-star class="w-5 h-5 text-star animate-pulse" style="animation-delay: 0.5s" />
                </div>
                <div class="space-y-1 text-sm text-ink/70">
                    <div>Moves: <strong class="text-ink font-bold">{{ $moves }}</strong></div>
                    @if($inCheck)
                        <div class="text-red-400">Checkmate!</div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Chess Board --}}
    <div class="flex justify-center">
        <div class="chess-board">
            {{-- Column labels (a-h) --}}
            <div class="chess-labels top-labels">
                @for($col = 0; $col < 8; $col++)
                    <div class="chess-label">{{ chr(97 + $col) }}</div>
                @endfor
            </div>

            {{-- Board with row labels --}}
            @for($row = 0; $row < 8; $row++)
                {{-- Row label (8-1) --}}
                <div class="chess-label side-label">{{ 8 - $row }}</div>

                {{-- Board squares --}}
                @for($col = 0; $col < 8; $col++)
                    @php
                        $piece = $this->getPieceAt($row, $col);
                        $isLightSquare = ($row + $col) % 2 === 0;
                        $isDarkSquare = ($row + $col) % 2 === 1;
                        $isSelected = $selectedSquare && $selectedSquare['row'] === $row && $selectedSquare['col'] === $col;
                        $isValidMove = $this->isValidMoveDestination($row, $col);
                    @endphp

                    <div class="chess-square {{ $isLightSquare ? 'light' : 'dark' }} {{ $isSelected ? 'selected' : '' }} {{ $isValidMove ? 'valid-move' : '' }}"
                         wire:click="selectSquare({{ $row }}, {{ $col }})"
                         tabindex="0"
                         aria-label="Square {{ chr(97 + $col) }}{{ 8 - $row }}{{ $piece ? ', ' . $this->getPieceDisplayName($piece) : ', empty' }}">

                        @if($piece)
                            <div class="chess-piece {{ $piece }}"
                                 aria-label="{{ $this->getPieceDisplayName($piece) }}">
                            </div>
                        @elseif($isValidMove)
                            <div class="valid-move-indicator"></div>
                        @endif
                    </div>
                @endfor

                {{-- Row label (8-1) --}}
                <div class="chess-label side-label">{{ 8 - $row }}</div>
            @endfor

            {{-- Column labels (a-h) --}}
            <div class="chess-labels bottom-labels">
                @for($col = 0; $col < 8; $col++)
                    <div class="chess-label">{{ chr(97 + $col) }}</div>
                @endfor
            </div>
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

        {{-- Chess Legend --}}
        <div class="flex justify-center">
            <div class="grid grid-cols-3 gap-4 text-xs text-ink/60">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-[hsl(var(--star))] border border-[hsl(var(--star)/.6)]"></div>
                    <span>White</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-[hsl(var(--constellation))] border border-[hsl(var(--constellation)/.6)]"></div>
                    <span>Black</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-[hsl(var(--star))] border-2 border-[hsl(var(--star)/.8)] relative">
                        <div class="absolute inset-0 flex items-center justify-center text-[8px] text-[hsl(var(--space-900))] font-bold">♔</div>
                    </div>
                    <span>King</span>
                </div>
            </div>
        </div>
    </div>
</x-ui.game-wrapper>

@push('styles')
<style>
.chess-piece {
    width: 100%;
    height: 100%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    transition: all 0.15s ease;
}

.chess-piece:hover {
    transform: scale(1.05);
}

.chess-piece.white_king {
    background-image: url('/images/chess/white_king.png');
}

.chess-piece.white_queen {
    background-image: url('/images/chess/white_queen.png');
}

.chess-piece.white_rook {
    background-image: url('/images/chess/white_rook.png');
}

.chess-piece.white_bishop {
    background-image: url('/images/chess/white_bishop.png');
}

.chess-piece.white_knight {
    background-image: url('/images/chess/white_knight.png');
}

.chess-piece.white_pawn {
    background-image: url('/images/chess/white_pawn.png');
}

.chess-piece.black_king {
    background-image: url('/images/chess/black_king.png');
}

.chess-piece.black_queen {
    background-image: url('/images/chess/black_queen.png');
}

.chess-piece.black_rook {
    background-image: url('/images/chess/black_rook.png');
}

.chess-piece.black_bishop {
    background-image: url('/images/chess/black_bishop.png');
}

.chess-piece.black_knight {
    background-image: url('/images/chess/black_knight.png');
}

.chess-piece.black_pawn {
    background-image: url('/images/chess/black_pawn.png');
}
</style>
@endpush

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


