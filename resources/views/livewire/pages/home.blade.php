<div>
    {{-- Skip to content for accessibility --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 btn-primary">
        Skip to content
    </a>

    {{-- Hero Section --}}
    <section class="section pt-10 md:pt-16" id="main-content">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="space-y-4">
                <x-ui.logo-lockup />

                <p class="kicker">{{ __('ui.tagline') }}</p>
                <h1 class="h1">{{ __('ui.tagline') }}</h1>
                <p class="p max-w-prose">
                    {{ __('ui.hero_body') }}
                </p>

                <x-ui.cta-row
                    :primaryHref="$firstPublishedGameSlug ? route('games.play', $firstPublishedGameSlug) : route('games.index')"
                    :primaryLabel="__('ui.cta_play')"
                    :secondaryHref="route('games.index')"
                    :secondaryLabel="__('ui.cta_browse')"
                />
            </div>

            {{-- Right column for minimal constellation accent (empty for now) --}}
            <div class="hidden md:block"></div>
        </div>
    </section>

    {{-- Available Now Section --}}
    <section class="mt-14 md:mt-16">
        <x-ui.section-header
            :kicker="__('ui.available_kicker')"
            :title="__('ui.available_title')"
            :subtitle="__('ui.available_sub')"
        />

        <div class="section mt-6 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
            @forelse($games as $game)
                <x-ui.card
                    :title="$game->title"
                    :subtitle="Str::limit($game->short_description, 90)"
                    :href="route('games.play', $game->slug)"
                    :icon="match($game->type) {
                        'puzzle' => 'cube-transparent',
                        'board' => 'rectangle-group',
                        'card' => 'rectangle-stack',
                        default => 'cursor-arrow-rays'
                    }"
                />
            @empty
                <x-ui.card 
                    :title="__('ui.coming_soon_title')" 
                    :subtitle="__('ui.coming_soon_sub')" 
                    icon="sparkles" 
                    :disabled="true"
                />
                <x-ui.card 
                    :title="__('ui.coming_soon_title')" 
                    :subtitle="__('ui.coming_soon_sub')" 
                    icon="sparkles" 
                    :disabled="true"
                />
                <x-ui.card 
                    :title="__('ui.coming_soon_title')" 
                    :subtitle="__('ui.coming_soon_sub')" 
                    icon="sparkles" 
                    :disabled="true"
                />
            @endforelse
        </div>
    </section>

    {{-- From the Studio Section (only if posts exist) --}}
    @if($posts->isNotEmpty())
        <section class="mt-14 md:mt-16">
            <x-ui.section-header
                :kicker="__('ui.studio_kicker')"
                :title="__('ui.studio_title')"
                :subtitle="__('ui.studio_sub')"
            />

            <div class="section mt-6 space-y-4">
                @foreach($posts as $post)
                    <x-ui.card
                        :title="$post->title"
                        :subtitle="Str::limit(strip_tags(Str::markdown($post->body_md)), 120)"
                        :href="route('blog.show', $post->slug)"
                        icon="document-text"
                    />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Footer --}}
    <footer class="section mt-16 mb-10 text-center text-sm text-white/60">
        <p>{{ __('ui.footer_note') }}</p>
        <div class="mt-3 flex justify-center gap-4 text-xs">
            <a href="https://github.com/bearjcc/website" target="_blank" rel="noopener" class="hover:text-white/80 transition">GitHub</a>
            <a href="{{ route('about') }}" class="hover:text-white/80 transition">About</a>
        </div>
    </footer>
</div>
