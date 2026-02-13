<div class="min-h-screen">
    {{-- Hero / Page intro --}}
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-2xl">
            <h1 class="h1">{{ __('ui.games_title') }}</h1>
            <p class="p mt-4 max-w-prose text-ink/70">{{ __('ui.games_subtitle') }}</p>
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
                    <x-ui.game-card
                        :href="route('games.play', $game->slug)"
                        :title="$game->title"
                        :motif="$game->getMotifKey()"
                        :aria="__('ui.play_game', ['game' => $game->title])"
                    />
                @endforeach
            </div>
        @endif
    </section>
</div>
