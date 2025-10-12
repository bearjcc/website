<div>
    {{-- Skip to content for accessibility --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 btn-primary">
        {{ __('ui.skip_to_content') }}
    </a>

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 glass border-b border-white/10">
        <div class="section py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-[color:var(--star)] hover:opacity-80 transition-opacity">
                <x-ui.logo-lockup :class="'w-[200px] md:w-[240px]'" />
            </a>
            
            <div class="flex gap-6 items-center">
                <a href="{{ route('games.index') }}" class="hover:text-[color:var(--star)] transition">{{ __('ui.nav_games') }}</a>
                <a href="{{ route('blog.index') }}" class="hover:text-[color:var(--star)] transition">{{ __('ui.nav_blog') }}</a>
                <a href="{{ route('about') }}" class="hover:text-[color:var(--star)] transition">{{ __('ui.nav_about') }}</a>
                
                @auth
                    @can('access-lore')
                        <a href="{{ route('lore.index') }}" class="hover:text-[color:var(--star)] transition">Lore</a>
                    @endcan
                    
                    @can('admin')
                        <a href="{{ route('admin.features') }}" class="hover:text-[color:var(--star)] transition">Admin</a>
                    @endcan
                    
                    <livewire:auth.logout />
                @else
                    <a href="{{ route('login') }}" class="hover:text-[color:var(--star)] transition">{{ __('ui.nav_login') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="section pt-12 md:pt-20" id="main-content">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="space-y-6">
                <x-ui.logo-lockup />

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
    <section class="mt-20 md:mt-24">
        <x-ui.section-header
            :kicker="__('ui.available_kicker')"
            :title="__('ui.available_title')"
            :subtitle="__('ui.available_sub')"
        />

        <div class="section mt-8 grid gap-5 sm:grid-cols-2 md:grid-cols-3">
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
        <section class="mt-20 md:mt-24">
            <x-ui.section-header
                :kicker="__('ui.studio_kicker')"
                :title="__('ui.studio_title')"
                :subtitle="__('ui.studio_sub')"
            />

            <div class="section mt-8 space-y-5">
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
    <footer class="section mt-24 mb-12 text-center">
        <p class="text-sm text-white/60 mb-4">{{ __('ui.footer_note') }}</p>
        <div class="flex justify-center gap-6 text-sm">
            <a href="https://github.com/bearjcc/website" target="_blank" rel="noopener" class="text-white/70 hover:text-[color:var(--star)] transition-colors font-medium">
                GitHub
            </a>
            <a href="{{ route('about') }}" class="text-white/70 hover:text-[color:var(--star)] transition-colors font-medium">
                About
            </a>
        </div>
    </footer>
</div>
