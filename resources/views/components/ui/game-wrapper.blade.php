@props([
    'title' => 'Game',
    'rules' => null,
    'showControls' => true,
])

<div class="section py-12 md:py-16">
    <div class="max-w-2xl mx-auto space-y-8">
        {{-- Back navigation --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 text-ink/70 hover:text-ink transition-colors"
               aria-label="{{ __('ui.back_to_games') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('ui.nav_games') }}</span>
            </a>
            <h1 class="h2 text-ink">{{ $title }}</h1>
        </div>

        {{-- Rules (if provided) --}}
        @if($rules)
            <details class="glass rounded-xl border border-[hsl(var(--border)/.1)] overflow-hidden">
                <summary class="px-6 py-3 cursor-pointer text-ink/80 hover:text-ink hover:bg-[hsl(var(--surface)/.08)] transition-colors list-none flex items-center justify-between">
                    <span>Rules</span>
                    <x-heroicon-o-chevron-down class="w-5 h-5" />
                </summary>
                <div class="px-6 py-4 border-t border-[hsl(var(--border)/.1)] space-y-2 text-ink/70 text-sm">
                    @if(is_array($rules))
                        @foreach($rules as $rule)
                            <p>{{ $rule }}</p>
                        @endforeach
                    @else
                        {!! $rules !!}
                    @endif
                </div>
            </details>
        @endif

        {{-- Game content slot --}}
        {{ $slot }}

        {{-- Standard controls slot (if provided) --}}
        @if($showControls && isset($controls))
            <div class="game-controls">
                {{ $controls }}
            </div>
        @endif
    </div>
</div>
