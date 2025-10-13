@props([
    'variant' => 'primary', // primary, secondary, ghost
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[44px] font-semibold text-base rounded-lg transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-star text-space-900 hover:bg-star/92 focus-visible:outline-star motion-safe:hover:-translate-y-0.5',
        'secondary' => 'bg-transparent text-ink border border-border/10 hover:border-ink/30 hover:bg-ink/5 focus-visible:outline-star',
        'ghost' => 'bg-transparent text-ink hover:bg-ink/5 focus-visible:outline-constellation',
        default => 'bg-star text-space-900 hover:bg-star/92 focus-visible:outline-star',
    };
    
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => trim("{$baseClasses} {$variantClasses}")]) }}
>
    @if($icon && $iconPosition === 'left')
        <x-dynamic-component 
            :component="'heroicon-o-' . $icon" 
            class="w-5 h-5" 
            aria-hidden="true"
        />
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <x-dynamic-component 
            :component="'heroicon-o-' . $icon" 
            class="w-5 h-5" 
            aria-hidden="true"
        />
    @endif
</{{ $tag }}>

