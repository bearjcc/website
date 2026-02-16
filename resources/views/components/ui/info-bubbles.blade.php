{{-- Rounded pill/bubble row: label + value. Place directly below board. --}}
<div class="flex flex-wrap justify-center gap-2 sm:gap-3" role="group" aria-label="Game stats">
    @foreach($bubbles as $bubble)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium {{ $bubble['highlight'] ?? false ? 'bg-star/15 text-star border border-star/30' : 'bg-[hsl(var(--surface)/.08)] text-ink/90 border border-[hsl(var(--border)/.2)]' }}">
            <span class="text-ink/70">{{ $bubble['label'] }}</span>
            <span>{{ $bubble['value'] }}</span>
        </span>
    @endforeach
</div>
