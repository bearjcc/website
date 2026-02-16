@php
    $playUrl = route('games.play', $game->slug);
@endphp

<div class="min-h-screen">
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-[960px] mx-auto">
            {{-- Game hero: motif + name + tagline --}}
            <div class="flex flex-wrap items-center gap-6 pb-8">
                <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                    <x-ui.game-motif :motif="$motif" class="opacity-80 text-ink/70 w-20 h-20" />
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="h1">{{ $game->title }}</h1>
                    @if($game->short_description)
                        <p class="text-lg text-ink/70 mt-1">{{ $game->short_description }}</p>
                    @endif
                </div>
            </div>

            {{-- Primary CTA --}}
            <div class="pb-8">
                <a href="{{ $playUrl }}" class="btn-primary">
                    {{ __('ui.cta_play') }}
                </a>
            </div>

            {{-- Optional: Rules (expandable) --}}
            @if($game->rules_md)
                <div class="pb-6" x-data="{ showRules: false }">
                    <button type="button"
                            @click="showRules = !showRules"
                            class="text-sm font-medium text-ink/80 hover:text-star transition-colors"
                            :aria-expanded="showRules">
                        Rules
                    </button>
                    <div x-show="showRules"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="mt-3 text-sm text-ink/70 prose prose-invert prose-sm max-w-none">
                        {!! \Illuminate\Support\Str::markdown($game->rules_md) !!}
                    </div>
                </div>
            @endif

            {{-- Optional: More games --}}
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
