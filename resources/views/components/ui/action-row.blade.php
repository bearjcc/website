{{-- Action row below bubbles: primary [New game], optional [Hint]. --}}
<div class="flex flex-wrap items-center justify-center gap-3 pt-2">
    @if(isset($newGameAction))
        <button type="button" wire:click="{{ $newGameAction }}" class="btn-primary">
            New game
        </button>
    @elseif(isset($newGameHref))
        <a href="{{ $newGameHref }}" class="btn-primary">New game</a>
    @endif
    @if(!empty($hintAction))
        <button type="button" wire:click="{{ $hintAction }}" class="btn-secondary">
            Hint
        </button>
    @endif
</div>
