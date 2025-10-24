@props([
    'class' => ''
])

{{-- Compact nav logo: bear icon only, 24-28px high, decorative only --}}
<div {{ $attributes->merge(['class' => 'flex items-center gap-2 ' . $class]) }}>
    <img
        src="{{ asset('bear.svg') }}"
        alt=""
        aria-hidden="true"
        class="w-6 h-6 md:w-7 md:h-7"
        loading="eager"
        decoding="async"
    />
    <span class="text-base md:text-lg font-semibold text-ink tracking-tight">ursa minor</span>
</div>

