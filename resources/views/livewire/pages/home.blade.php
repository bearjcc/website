<div>
    <x-slot:title>Home</x-slot:title>

    <section class="pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="section">
            <div class="max-w-3xl mx-auto text-center space-y-8">
                <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" data-um-lockup="hero" />

                <div class="space-y-4">
                    <p class="kicker">{{ __('ui.home_kicker') }}</p>
                    <h1 class="h1">{{ __('ui.home_headline') }}</h1>
                    <p class="text-lg text-[color:var(--ink-muted)] max-w-2xl mx-auto">
                        {{ __('ui.home_lead') }}
                    </p>
                    <p class="text-sm text-ink/60">{{ __('ui.tagline') }}</p>
                </div>

                <div class="pt-2">
                    <x-ui.cta-row
                        :primaryHref="route('games.index').'#quiet-picks'"
                        :primaryLabel="__('ui.cta_quiet_picks')"
                        :secondaryHref="route('games.index')"
                        :secondaryLabel="__('ui.cta_see_all_games')"
                        data-um-goal="hero_play_click"
                    />
                </div>

                @if($featuredGame)
                    <p class="text-sm text-ink/65">
                        {{ __('ui.home_featured_try') }}
                        <a href="{{ route('games.play', $featuredGame->slug) }}" class="text-ink hover:text-star transition-colors">{{ $featuredGame->title }}</a>{{ __('ui.home_featured_end') }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section id="sites-and-games" class="scroll-mt-24 py-8 md:py-12">
        <div class="section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-8 space-y-3">
                    <p class="kicker">{{ __('ui.home_all_games_heading') }}</p>
                    <h2 class="h3 text-ink">{{ __('ui.home_all_games_heading') }}</h2>
                    <p class="p text-ink/70">{{ __('ui.home_all_games_lead') }}</p>
                </div>

                <div
                    id="home-sites-and-games-grid"
                    class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                    @foreach($sisterSites as $site)
                        <x-ui.sister-site-card
                            :href="$site['href']"
                            :title="$site['title']"
                            :blurb="$site['blurb']"
                            :variant="$site['variant']"
                        />
                    @endforeach
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

    @if($relaxingGames->isNotEmpty())
        <section class="pb-16 md:pb-20">
            <div class="section">
                <div class="max-w-5xl mx-auto">
                    <div class="max-w-2xl space-y-3 mb-8">
                        <p class="kicker">{{ __('ui.games_quiet_title') }}</p>
                        <h2 class="h3">{{ __('ui.home_quiet_heading') }}</h2>
                        <p class="p text-ink/70">{{ __('ui.games_quiet_subtitle') }}</p>
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
</div>
