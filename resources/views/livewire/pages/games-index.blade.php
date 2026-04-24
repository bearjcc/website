<div class="min-h-screen">
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-3xl space-y-6">
            <div class="space-y-4">
                <p class="kicker">{{ __('ui.games_index_kicker') }}</p>
                <h1 class="h1">{{ __('ui.games_hero') }}</h1>
                <p class="p mt-4 max-w-prose text-ink/70">
                    {{ __('ui.games_hero_lead') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="#quiet-picks" class="btn-primary">{{ __('ui.games_skip_quiet') }}</a>
                <a href="#steady-picks" class="btn-secondary">{{ __('ui.games_skip_steady') }}</a>
                <a href="#lively-picks" class="btn-secondary">{{ __('ui.games_skip_lively') }}</a>
            </div>
        </div>
    </section>

    <section class="section pb-20 md:pb-24">
        @if($games->isEmpty())
            <div class="text-center py-16">
                <x-heroicon-o-sparkles class="w-12 h-12 mx-auto text-ink/40 mb-4" />
                <p class="p">{{ __('ui.games_empty') }}</p>
            </div>
        @else
            <div class="space-y-16">
                <section id="quiet-picks" class="scroll-mt-28">
                    <div class="max-w-2xl space-y-3 mb-8">
                        <p class="kicker">{{ __('ui.games_quiet_title') }}</p>
                        <h2 class="h3">{{ __('ui.games_quiet_title') }}</h2>
                        <p class="p text-ink/70">{{ __('ui.games_quiet_subtitle') }}</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($paceGroups['quiet'] as $game)
                            <article class="glass p-5 md:p-6 flex flex-col gap-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-2">
                                        <h3 class="h6">{{ $game->title }}</h3>
                                        <p class="text-sm text-ink/70">{{ $game->bestFor() }}</p>
                                    </div>
                                    <x-ui.game-motif :motif="$game->getMotifKey()" class="w-12 h-12 shrink-0 text-ink/70 opacity-80" />
                                </div>

                                <p class="text-sm text-ink/60">{{ $game->short_description }}</p>

                                <div class="flex flex-wrap gap-3 pt-1">
                                    <a href="{{ route('games.play', $game->slug) }}" class="btn-primary" aria-label="{{ __('ui.play_game', ['game' => $game->title]) }}">{{ __('ui.cta_play_now') }}</a>
                                    <a href="{{ route('games.show', $game->slug) }}" class="btn-secondary">About game</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section id="steady-picks" class="scroll-mt-28">
                    <div class="max-w-2xl space-y-3 mb-8">
                        <p class="kicker">{{ __('ui.games_steady_title') }}</p>
                        <h2 class="h3">{{ __('ui.games_steady_title') }}</h2>
                        <p class="p text-ink/70">{{ __('ui.games_steady_subtitle') }}</p>
                    </div>

                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                        @foreach($paceGroups['steady'] as $game)
                            <x-ui.game-card
                                :href="route('games.show', $game->slug)"
                                :title="$game->title"
                                :motif="$game->getMotifKey()"
                                :aria="__('ui.play_game', ['game' => $game->title])"
                            />
                        @endforeach
                    </div>
                </section>

                <section id="lively-picks" class="scroll-mt-28">
                    <div class="max-w-2xl space-y-3 mb-8">
                        <p class="kicker">{{ __('ui.games_lively_title') }}</p>
                        <h2 class="h3">{{ __('ui.games_lively_title') }}</h2>
                        <p class="p text-ink/70">{{ __('ui.games_lively_subtitle') }}</p>
                    </div>

                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                        @foreach($paceGroups['lively'] as $game)
                            <x-ui.game-card
                                :href="route('games.show', $game->slug)"
                                :title="$game->title"
                                :motif="$game->getMotifKey()"
                                :aria="__('ui.play_game', ['game' => $game->title])"
                            />
                        @endforeach
                    </div>
                </section>
            </div>
        @endif
    </section>
</div>
