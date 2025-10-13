@props([
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'type' => 'text',
])

@php
    $inputId = $attributes->get('id') ?? 'input-' . uniqid();
    $inputClasses = 'w-full px-4 py-3 rounded-lg bg-surface/5 border border-border/10 text-ink placeholder:text-ink-muted/50 focus:outline-none focus:ring-2 focus:ring-star/50 focus:border-star transition-all';
    $errorClasses = $error ? 'border-red-400 focus:ring-red-400/50 focus:border-red-400' : '';
@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-ink">
            {{ $label }}
            @if($required)
                <span class="text-star" aria-label="required">*</span>
            @endif
        </label>
    @endif
    
    <input
        type="{{ $type }}"
        id="{{ $inputId }}"
        {{ $attributes->merge(['class' => trim("{$inputClasses} {$errorClasses}")]) }}
    >
    
    @if($hint && !$error)
        <p class="text-sm text-ink-muted">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-red-400">{{ $error }}</p>
    @endif
</div>

