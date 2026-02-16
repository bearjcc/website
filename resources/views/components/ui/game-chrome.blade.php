{{-- Game chrome bar: game name (left), turn/score (right). No actions; actions live below board. --}}
<header class="border-b border-[hsl(var(--border)/.15)] bg-[hsl(var(--space-800)/.5)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 min-h-[48px]">
            <div class="flex items-center gap-3">
                @if(isset($gamePageUrl))
                    <a href="{{ $gamePageUrl }}" class="text-ink/70 hover:text-star transition-colors text-sm" aria-label="Back to {{ $gameTitle }}">Games</a>
                    <span class="text-ink/40" aria-hidden="true">/</span>
                @endif
                <h2 class="text-lg font-semibold text-ink">{{ $gameTitle }}</h2>
            </div>
            <div class="text-sm font-medium text-ink/90">
                {{ $rightContent ?? '' }}
            </div>
        </div>
    </div>
</header>
