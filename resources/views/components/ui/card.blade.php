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
    <div class="flex items-start gap-3">
        @if($icon)
            <x-dynamic-component 
                :component="'heroicon-o-' . $icon" 
                class="w-6 h-6 text-white/70 shrink-0 mt-0.5" 
            />
        @endif

        <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                @if($meta)
                    <span class="text-xs text-white/50 whitespace-nowrap">{{ $meta }}</span>
                @endif
            </div>

            @if($subtitle)
                <p class="p mt-1 line-clamp-2">{{ $subtitle }}</p>
            @endif

            @if($href && !$disabled)
                <div class="mt-3 inline-flex items-center gap-1 text-sm text-white/80">
                    <span>Open</span>
                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform group-hover:translate-x-0.5" />
                </div>
            @endif
        </div>
    </div>
</{{ $tag }}>

