<div>
    <x-slot:title>Home</x-slot:title>

    <section class="pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="section">
            <div class="max-w-3xl mx-auto text-center space-y-8">
                <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" data-um-lockup="hero" />

                <div class="space-y-4">
                    <p class="kicker">A quiet corner for browser games</p>
                    <h1 class="h1">Settle in. Pick a small game. Start softly.</h1>
                    <p class="text-lg text-[color:var(--ink-muted)] max-w-2xl mx-auto">
                        Choose something gentle, open it in your browser, and let the rest of the page stay out of your way.
                    </p>
                    <p class="text-sm text-ink/60">{{ __('ui.tagline') }}</p>
                </div>

                <div class="pt-2">
                    <x-ui.cta-row
                        :primaryHref="route('games.index').'#quiet-picks'"
                        :primaryLabel="__('ui.cta_start_relaxing')"
                        :secondaryHref="route('games.index')"
                        :secondaryLabel="__('ui.cta_see_all_games')"
                        data-um-goal="hero_play_click"
                    />
                </div>

                @if($featuredGame)
                    <p class="text-sm text-ink/65">
                        If you want the easiest start, begin with
                        <a href="{{ route('games.play', $featuredGame->slug) }}" class="text-ink hover:text-star transition-colors">{{ $featuredGame->title }}</a>.
                    </p>
                @endif
            </div>
        </div>
    </section>

    @if($relaxingGames->isNotEmpty())
        <section class="pb-16 md:pb-20">
            <div class="section">
                <div class="max-w-5xl mx-auto">
                    <div class="max-w-2xl space-y-3 mb-8">
                        <p class="kicker">Quiet path</p>
                        <h2 class="h3">Start with something gentle.</h2>
                        <p class="p text-ink/70">These are the easiest games to slip into when you want something calm right away.</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach($relaxingGames as $game)
                            <article class="glass p-5 md:p-6 flex flex-col gap-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-ink/50">{{ $game->paceLabel() }}</p>
                                        <h3 class="h6 mt-2">{{ $game->title }}</h3>
                                    </div>
                                    <div class="w-12 h-12 shrink-0 text-ink/70">
                                        <x-ui.game-motif :motif="$game->getMotifKey()" class="w-12 h-12 opacity-80" />
                                    </div>
                                </div>

                                <p class="text-sm text-ink/70">{{ $game->bestFor() }}</p>
                                <p class="text-sm text-ink/60">{{ $game->short_description }}</p>

                                <div class="pt-1">
                                    <a href="{{ route('games.play', $game->slug) }}" class="btn-primary w-full">
                                        {{ __('ui.cta_play_now') }}
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="pb-16 md:pb-20">
        <div class="section">
            <div class="max-w-5xl mx-auto">
                <div class="max-w-2xl space-y-3 mb-8">
                    <p class="kicker">User story</p>
                    <h2 class="h3">A relaxing visit should take three quiet steps.</h2>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <article class="glass p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-ink/50">1</p>
                        <h3 class="h6 mt-3">Arrive without pressure</h3>
                        <p class="text-sm text-ink/70 mt-3">The homepage offers one calm action first: start relaxing. No account wall, no loud promotion, no clutter.</p>
                    </article>
                    <article class="glass p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-ink/50">2</p>
                        <h3 class="h6 mt-3">Choose by energy</h3>
                        <p class="text-sm text-ink/70 mt-3">The games page groups titles by pace so the visitor can pick a quiet, steady, or livelier game without reading much.</p>
                    </article>
                    <article class="glass p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-ink/50">3</p>
                        <h3 class="h6 mt-3">Play right away</h3>
                        <p class="text-sm text-ink/70 mt-3">Each game page reassures the player, keeps the rules optional, and makes the play button the obvious next step.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-16 pb-20">
        <div class="section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-8 space-y-3">
                    <p class="kicker">All games</p>
                    <h2 class="h3 text-ink">Wander a little.</h2>
                    <p class="p text-ink/70">If you would rather browse at your own pace, every game is here.</p>
                </div>

                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                    @foreach($games as $game)
                        <x-ui.game-card
                            :href="route('games.show', $game->slug)"
                            :title="$game->title"
                            :motif="$game->getMotifKey()"
                        />
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
