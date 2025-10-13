<x-layouts.app>
    <x-slot:title>{{ __('ui.tagline') }} — Ursa Minor</x-slot:title>

    {{-- Hero Section - Minimal copy, centered --}}
    <section class="pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="section">
            <div class="max-w-2xl mx-auto text-center space-y-8">
                <x-ui.logo-lockup class="w-[280px] md:w-[360px] mx-auto" />
                
                <h1 class="h1">{{ __('ui.hero_headline') }}</h1>

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

    {{-- Available Now Section - Visual-first games grid --}}
    <section class="py-12 md:py-16">
        <div class="section">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @php
                    $publishedGames = \App\Models\Game::where('status', 'published')->get();
                    
                    // Map game slugs to motifs
                    $motifMap = [
                        'tic-tac-toe' => 'tictactoe',
                        'connect-4' => 'connect4',
                        'sudoku' => 'sudoku',
                        'minesweeper' => 'minesweeper',
                        'snake' => 'snake',
                        '2048' => '2048',
                    ];
                @endphp

                @foreach($publishedGames as $game)
                    <x-ui.game-card
                        :href="route('games.play', $game->slug)"
                        :title="$game->title"
                        :motif="$motifMap[$game->slug] ?? null"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest Notes Section - Minimal --}}
    @php
        $latestPosts = \App\Models\Post::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    @endphp

    @if($latestPosts->count() > 0)
        <section class="py-12 md:py-16">
            <div class="section">
                <h2 class="h5 mb-6 text-center text-[color:var(--ink-muted)]">{{ __('ui.latest_notes') }}</h2>
                
                <div class="max-w-xl mx-auto space-y-3">
                    @foreach($latestPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" 
                           class="group block glass p-4 hover:border-[color:var(--ink)]/20 transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--constellation)]">
                            <div class="flex items-baseline justify-between gap-4">
                                <h3 class="text-[color:var(--ink)] group-hover:text-[color:var(--star)] transition-colors text-base">
                                    {{ $post->title }}
                                </h3>
                                <time class="text-xs text-[color:var(--ink-muted)] shrink-0" datetime="{{ $post->created_at->toIso8601String() }}">
                                    {{ $post->created_at->format('M j') }}
                                </time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
