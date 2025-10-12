<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-star-yellow mb-4">{{ $game->title }}</h1>
    
    @if($game->short_description)
        <p class="text-white/70 mb-6">{{ $game->short_description }}</p>
    @endif
    
    <div class="card mb-6">
        <div id="game-canvas" class="min-h-[400px] flex items-center justify-center bg-black/30 rounded">
            <p class="text-white/50">Game canvas area - integrate Livewire game component here</p>
        </div>
    </div>
    
    @if($game->rules_md)
        <div class="card prose">
            <h2>How to Play</h2>
            <div>{!! \Illuminate\Support\Str::markdown($game->rules_md) !!}</div>
        </div>
    @endif
</div>
