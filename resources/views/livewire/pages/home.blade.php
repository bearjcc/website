<div>
    {{-- Hero Section --}}
    <section class="section pt-16 md:pt-24" id="main-content">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <x-ui.logo-lockup />
                
                <p class="kicker">A small game studio</p>

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

            {{-- Right column for minimal constellation accent --}}
            <div class="hidden md:block flex justify-center items-center">
                <div class="w-32 h-32 opacity-20">
                    <svg viewBox="0 0 100 100" class="w-full h-full text-[color:var(--constellation)]">
                        <!-- Simple constellation pattern -->
                        <circle cx="20" cy="30" r="1.5" fill="currentColor" />
                        <circle cx="40" cy="20" r="1" fill="currentColor" />
                        <circle cx="60" cy="35" r="1.5" fill="currentColor" />
                        <circle cx="80" cy="25" r="1" fill="currentColor" />
                        <circle cx="25" cy="60" r="1" fill="currentColor" />
                        <circle cx="45" cy="70" r="1.5" fill="currentColor" />
                        <circle cx="70" cy="65" r="1" fill="currentColor" />
                        <circle cx="85" cy="75" r="1.5" fill="currentColor" />
                        
                        <!-- Connect some stars with subtle lines -->
                        <line x1="20" y1="30" x2="40" y2="20" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                        <line x1="40" y1="20" x2="60" y2="35" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                        <line x1="60" y1="35" x2="80" y2="25" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                        <line x1="25" y1="60" x2="45" y2="70" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                        <line x1="45" y1="70" x2="70" y2="65" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                        <line x1="70" y1="65" x2="85" y2="75" stroke="currentColor" stroke-width="0.5" opacity="0.3" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- Available Now Section --}}
    <section class="mt-24 md:mt-32">
        <x-ui.section-header
            :kicker="__('ui.available_kicker')"
            :title="__('ui.available_title')"
            :subtitle="__('ui.available_sub')"
        />

        <div class="section mt-8 grid gap-6 sm:grid-cols-2 md:grid-cols-3">
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
        <section class="mt-24 md:mt-32 mb-24">
            <x-ui.section-header
                :kicker="__('ui.studio_kicker')"
                :title="__('ui.studio_title')"
                :subtitle="__('ui.studio_sub')"
            />

            <div class="section mt-8 space-y-6">
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
    @else
        {{-- Add bottom margin when no posts section --}}
        <div class="mb-24"></div>
    @endif
</div>
