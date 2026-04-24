@php
    $playUrl = route('games.play', $game->slug);
    $browseAnchor = match ($game->pace()) {
        'quiet' => 'quiet-picks',
        'lively' => 'lively-picks',
        default => 'steady-picks',
    };
@endphp

<div class="min-h-screen">
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-[960px] mx-auto">
            <div class="flex flex-wrap items-center gap-6 pb-8">
                <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                    <x-ui.game-motif :motif="$motif" class="opacity-80 text-ink/70 w-20 h-20" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="kicker mb-3">{{ $game->paceLabel() }}</p>
                    <h1 class="h1">{{ $game->title }}</h1>
                    @if($game->short_description)
                        <p class="text-lg text-ink/70 mt-1">{{ $game->short_description }}</p>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto] items-start pb-8">
                <div class="glass p-5">
                    <p class="text-sm uppercase tracking-[0.16em] text-ink/50">Best for</p>
                    <p class="text-base text-ink mt-3">{{ $game->bestFor() }}</p>
                    <p class="text-sm text-ink/65 mt-3">{{ __('ui.game_show_play_blurb') }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ $playUrl }}" class="btn-primary">
                        {{ __('ui.cta_play_now') }}
                    </a>
                    <a href="{{ route('games.index') }}#{{ $browseAnchor }}" class="btn-secondary">
                        {{ $game->pace() === 'quiet' ? __('ui.cta_quiet_picks') : __('ui.cta_browse') }}
                    </a>
                </div>
            </div>

            @if($game->rules_md)
                <div class="pb-6" x-data="{ showRules: false }" id="rules-block-{{ $game->slug }}">
                    <button type="button"
                            id="rules-toggle-{{ $game->slug }}"
                            aria-controls="rules-panel-{{ $game->slug }}"
                            @click="showRules = !showRules"
                            class="text-sm font-medium text-ink/80 hover:text-star transition-colors"
                            :aria-expanded="showRules">
                        {{ __('ui.see_more_rules') }}<span class="sr-only">{{ __('ui.see_more_rules_screen_reader') }}</span>
                    </button>
                    <div id="rules-panel-{{ $game->slug }}"
                         x-show="showRules"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="mt-3 text-sm text-ink/70 prose prose-invert prose-sm max-w-none">
                        {!! \Illuminate\Support\Str::markdown($game->rules_md) !!}
                    </div>
                </div>
            @endif

            @if($game->pace() !== 'quiet' && $calmerGames->isNotEmpty())
                <div class="pb-8">
                    <div class="glass p-5 md:p-6">
                        <p class="text-sm uppercase tracking-[0.16em] text-ink/50">{{ __('ui.game_show_slower_paced') }}</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            @foreach($calmerGames as $calmerGame)
                                <a href="{{ route('games.play', $calmerGame->slug) }}" class="btn-secondary">{{ $calmerGame->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($otherGames->isNotEmpty())
                <p class="text-sm text-ink/60">
                    More games:
                    @foreach($otherGames as $g)
                        <a href="{{ route('games.show', $g->slug) }}" class="text-ink/70 hover:text-star transition-colors">{{ $g->title }}</a>{{ $loop->last ? '' : ', ' }}
                    @endforeach
                </p>
            @endif
        </div>
    </section>
</div>
