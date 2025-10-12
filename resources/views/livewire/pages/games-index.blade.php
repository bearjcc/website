<div>
    <h1 class="text-4xl font-bold text-star-yellow mb-8">Browser Games</h1>
    
    @if($games->count() > 0)
        <div class="feature-grid">
            @foreach($games as $game)
                <x-public-card>
                    <h3 class="text-xl font-semibold text-star-yellow mb-2">{{ $game->title }}</h3>
                    <p class="text-sm text-white/70 mb-4">{{ $game->short_description }}</p>
                    <a href="{{ route('games.play', $game->slug) }}" class="btn btn-primary">
                        Play Now
                    </a>
                </x-public-card>
            @endforeach
        </div>
    @else
        <x-public-card>
            <p class="text-center text-white/70">No games available yet. Check back soon!</p>
        </x-public-card>
    @endif
</div>
