@props([
    'class' => ''
])

{{-- Compact nav logo: bear icon only, 24-28px high, with accessible link to home --}}
<a 
    href="{{ route('home') }}" 
    aria-label="Ursa Minor — Home"
    {{ $attributes->merge(['class' => 'flex items-center gap-2 transition-opacity hover:opacity-90 focus-visible:outline-2 focus-visible:outline-[color:var(--star)] focus-visible:outline-offset-2 rounded ' . $class]) }}
>
    <img 
        src="{{ asset('bear.svg') }}" 
        alt=""
        aria-hidden="true"
        class="w-6 h-6 md:w-7 md:h-7" 
        loading="eager"
        decoding="async"
    />
    <span class="text-base md:text-lg font-semibold text-[color:var(--ink)] tracking-tight">ursa minor</span>
</a>

