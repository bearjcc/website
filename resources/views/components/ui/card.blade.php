@props([
    'title',
    'subtitle' => null,
    'href' => null,
    'icon' => null,
    'meta' => null,
    'disabled' => false,
])

@php
    $tag = $href && !$disabled ? 'a' : 'div';
    $baseClasses = 'group glass block p-5 md:p-6 transition-colors';
    $interactiveClasses = $href && !$disabled ? 'hover:border-white/20 cursor-pointer' : '';
    $disabledClasses = $disabled ? 'opacity-60' : '';
@endphp

<{{ $tag }}
    @if($href && !$disabled) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => trim("{$baseClasses} {$interactiveClasses} {$disabledClasses}")
    ]) }}
>
    <div class="flex items-start gap-4">
        @if($icon)
            <x-dynamic-component 
                :component="'heroicon-o-' . $icon" 
                class="w-5 h-5 text-[color:var(--constellation)] shrink-0 mt-1" 
                aria-hidden="true"
            />
        @endif

        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-semibold text-[color:var(--ink)] leading-snug">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-sm text-[color:var(--ink-muted)] mt-2 leading-relaxed line-clamp-2">{{ $subtitle }}</p>
            @endif
            @if($meta)
                <p class="text-xs text-[color:var(--ink-muted)] mt-3">{{ $meta }}</p>
            @endif
        </div>

        @if($href && !$disabled)
            <x-heroicon-o-chevron-right 
                class="w-4 h-4 text-[color:var(--ink-muted)] group-hover:text-[color:var(--star)] transition-colors shrink-0 mt-1" 
                aria-hidden="true"
            />
        @endif
    </div>
</{{ $tag }}>

