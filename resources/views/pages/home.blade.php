<x-layouts.app>
    <x-slot:title>{{ __('ui.tagline') }} — Ursa Minor</x-slot:title>

    {{-- Hero Section with increased breathing room --}}
    <section class="pt-24 md:pt-32 pb-16 md:pb-24">
        <div class="section">
            <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-center">
                {{-- Left column: Full lockup and messaging --}}
                <div class="text-center md:text-left space-y-6" data-um-hero-lockup>
                    <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto md:mx-0" />
                    
                    <h1 class="h1 mt-6">{{ __('ui.hero_headline') }}</h1>
                    
                    <p class="text-lg text-[color:var(--ink-muted)] leading-relaxed max-w-[35em] mx-auto md:mx-0">
                        {{ __('ui.hero_body') }}
                    </p>

                    <div class="pt-4">
                        <x-ui.cta-row
                            :primaryHref="route('games.index')"
                            :primaryLabel="__('ui.cta_play')"
                            :secondaryHref="route('blog.index')"
                            :secondaryLabel="__('ui.cta_browse')"
                            data-um-goal="hero_play_click"
                        />
                    </div>
                </div>

                {{-- Right column: Decorative starfield only (CSS) --}}
                <div class="hidden md:block relative h-80" aria-hidden="true">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-[color:var(--space-800)] to-[color:var(--space-900)] opacity-50"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Available Now Section --}}
    <section class="py-12 md:py-16">
        <x-ui.section-header
            :kicker="__('ui.available_kicker')"
            :title="__('ui.available_title')"
            :subtitle="__('ui.available_sub')"
        />

        <div class="section mt-12">
            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $publishedGames = \App\Models\Game::where('status', 'published')->limit(3)->get();
                    $gameCount = $publishedGames->count();
                    $placeholdersNeeded = max(0, 3 - $gameCount);
                @endphp

                {{-- Published games --}}
                @foreach($publishedGames as $game)
                    @php
                        // Select icon based on game type
                        $icon = match($game->type ?? 'puzzle') {
                            'puzzle' => 'cube-transparent',
                            'board' => 'rectangle-group',
                            'card' => 'rectangle-stack',
                            default => 'puzzle-piece',
                        };
                    @endphp
                    <x-ui.card
                        :title="$game->title"
                        :subtitle="$game->short_description"
                        :href="route('games.play', $game->slug)"
                        :icon="$icon"
                    />
                @endforeach

                {{-- Placeholders if fewer than 3 games --}}
                @for($i = 0; $i < $placeholdersNeeded; $i++)
                    <x-ui.card
                        :title="__('ui.coming_soon_placeholder')"
                        :subtitle="__('ui.coming_soon_placeholder_sub')"
                        :disabled="true"
                    />
                @endfor
            </div>
        </div>
    </section>

    {{-- Latest Notes Section --}}
    <section class="py-12 md:py-16">
        <x-ui.section-header
            :kicker="__('ui.studio_kicker')"
            :title="__('ui.latest_notes')"
            :subtitle="__('ui.latest_notes_sub')"
        />

        <div class="section mt-12">
            @php
                $latestPosts = \App\Models\Post::where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp

            @if($latestPosts->count() > 0)
                <div class="space-y-4">
                    @foreach($latestPosts as $post)
                        <x-ui.card
                            :title="$post->title"
                            :href="route('blog.show', $post->slug)"
                            :meta="$post->created_at->diffForHumans()"
                            icon="document-text"
                        />
                    @endforeach
                </div>
            @else
                <div class="text-center text-[color:var(--ink-muted)] py-8">
                    <p>{{ __('ui.coming_soon_sub') }}</p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
