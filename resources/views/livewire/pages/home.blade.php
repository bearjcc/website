<div>
    <x-slot:title>Home</x-slot:title>

    {{-- Hero Section - Minimal copy, centered --}}
    <section class="pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="section">
            <div class="max-w-2xl mx-auto text-center space-y-8">
                <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" data-um-lockup="hero" />

                <h1 class="h1">{{ __('ui.hero_headline') }}</h1>
                <p class="text-lg text-[color:var(--ink-muted)]">{{ __('ui.tagline') }}</p>

                <div class="pt-2">
                    <x-ui.cta-row
                        :primaryHref="route('games.index')"
                        :primaryLabel="__('ui.cta_play')"
                        :secondaryHref="route('games.index')"
                        :secondaryLabel="__('ui.cta_browse')"
                        data-um-goal="hero_play_click"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- Games Section - Clear and obvious --}}
    <section class="py-12 md:py-16 pb-20">
        <div class="section">
            <div class="max-w-4xl mx-auto">
                <h2 class="h3 text-center text-ink mb-8">Free Games to Play</h2>
                
                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                    @foreach($games as $game)
                        <x-ui.game-card
                            :href="route('games.play', $game->slug)"
                            :title="$game->title"
                            :motif="$game->getMotifKey()"
                        />
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
