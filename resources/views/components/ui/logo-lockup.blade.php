@props([
    'class' => 'w-[260px] md:w-[320px] h-auto'
])

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="flex items-center gap-3">
        <img 
            src="{{ asset('bear.svg') }}" 
            alt="Ursa Minor bear constellation" 
            class="w-16 h-16 md:w-20 md:h-20" 
            loading="eager"
            decoding="async"
        />
        <div class="flex flex-col">
            <span class="text-3xl md:text-4xl font-bold tracking-tight text-white">ursa</span>
            <span class="text-2xl md:text-3xl font-light tracking-wide text-white/80">minor</span>
        </div>
    </div>
</div>

