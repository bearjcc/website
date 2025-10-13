@props([
    'heading' => null,
    'href' => null,
    'icon' => null,
    'interactive' => false,
])

@php
    $tag = $href ? 'a' : 'div';
    $baseClasses = 'glass block rounded-xl p-6';
    $interactiveClasses = ($href || $interactive)
        ? 'group transition-all duration-200 hover:border-ink/20 cursor-pointer motion-safe:hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2'
        : '';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => trim("{$baseClasses} {$interactiveClasses}")]) }}
>
    @if($icon || $heading)
        <div class="flex items-start gap-4 mb-4">
            @if($icon)
                <div class="shrink-0">
                    <x-dynamic-component 
                        :component="'heroicon-o-' . $icon" 
                        class="w-6 h-6 text-constellation" 
                        aria-hidden="true"
                    />
                </div>
            @endif
            
            @if($heading)
                <h3 class="text-xl font-semibold text-ink leading-tight flex-1">
                    {{ $heading }}
                    @if($href)
                        <span class="inline-block ml-2 text-xs text-ink-muted group-hover:text-star transition-colors">
                            →
                        </span>
                    @endif
                </h3>
            @endif
        </div>
    @endif
    
    <div class="text-ink-muted space-y-3">
        {{ $slot }}
    </div>
</{{ $tag }}>

