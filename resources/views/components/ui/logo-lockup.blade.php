@props([
    'class' => 'w-auto h-auto'
])

<div data-um-hero-lockup {{ $attributes->merge(['class' => $class]) }}>
    <div class="flex items-center gap-3">
        <span class="text-3xl md:text-4xl font-bold tracking-tight text-ink">ursa</span>
        <img 
            src="{{ asset('bear.svg') }}" 
            alt="Ursa Minor bear constellation" 
            class="w-16 h-16 md:w-20 md:h-20" 
            loading="eager"
            decoding="async"
        />
        <span class="text-3xl md:text-4xl font-bold tracking-tight text-ink">minor</span>
    </div>
</div>

