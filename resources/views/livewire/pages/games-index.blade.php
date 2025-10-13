<div class="min-h-screen">
    {{-- Hero / Page intro --}}
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-2xl">
            <p class="kicker">{{ __('ui.games_kicker') }}</p>
            <h1 class="h1 mt-2">{{ __('ui.games_title') }}</h1>
            <p class="p mt-4 max-w-prose">{{ __('ui.games_subtitle') }}</p>
        </div>
    </section>

    {{-- Games grid --}}
    <section class="section pb-20 md:pb-24">
        @if($games->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-16">
                <x-heroicon-o-sparkles class="w-12 h-12 mx-auto text-ink/40 mb-4" />
                <p class="p">{{ __('ui.games_empty') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($games as $game)
                    @php
                        // Map game type or slug to visual motif
                        $motif = match($game->slug) {
                            'tic-tac-toe' => 'tictactoe',
                            'connect-4' => 'connect4',
                            'twenty-forty-eight', '2048' => '2048',
                            default => match($game->type) {
                                'board' => 'board',
                                'puzzle' => 'puzzle',
                                'card' => 'cards',
                                default => $game->slug,
                            },
                        };
                    @endphp
                    <x-ui.game-card
                        :href="route('games.play', $game->slug)"
                        :title="$game->title"
                        :motif="$motif"
                        :aria="__('ui.play_game', ['game' => $game->title])"
                    />
                @endforeach
            </div>
        @endif
    </section>
</div>
