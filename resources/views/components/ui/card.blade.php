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
    $baseClasses = 'group glass block p-5 md:p-6 transition-all duration-200';
    $interactiveClasses = $href && !$disabled 
        ? 'hover:border-ink/20 cursor-pointer motion-safe:hover:-translate-y-[1px]' 
        : '';
    $disabledClasses = $disabled ? 'opacity-60' : '';
    $focusClasses = $href && !$disabled 
        ? 'focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2 rounded-xl' 
        : '';
@endphp

<{{ $tag }}
    @if($href && !$disabled) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => trim("{$baseClasses} {$interactiveClasses} {$disabledClasses} {$focusClasses}")
    ]) }}
>
    <div class="flex items-start gap-4">
        @if($icon)
            <x-dynamic-component 
                :component="'heroicon-o-' . $icon" 
                class="w-5 h-5 md:w-6 md:h-6 text-constellation shrink-0" 
                style="margin-top: 0.125rem;"
                aria-hidden="true"
            />
        @endif

        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-semibold text-ink leading-snug flex items-baseline gap-2">
                {{ $title }}
                @if($href && !$disabled)
                    <span class="text-xs text-ink-muted group-hover:text-star transition-colors">Open</span>
                @endif
            </h3>
            @if($subtitle)
                <p class="text-sm text-ink-muted mt-2 leading-relaxed">{{ $subtitle }}</p>
            @endif
            @if($meta)
                <p class="text-xs text-ink-muted mt-3">{{ $meta }}</p>
            @endif
        </div>

        @if($href && !$disabled)
            <x-heroicon-o-chevron-right 
                class="w-4 h-4 text-ink-muted group-hover:text-star transition-colors shrink-0" 
                style="margin-top: 0.125rem;"
                aria-hidden="true"
            />
        @endif
    </div>
</{{ $tag }}>

