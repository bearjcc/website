@props([
    'primaryHref', 
    'primaryLabel',
    'secondaryHref' => null, 
    'secondaryLabel' => null
])

<div class="flex flex-wrap gap-3">
    <a href="{{ $primaryHref }}" class="btn-primary" {{ $attributes->only('data-um-goal') }}>{{ $primaryLabel }}</a>

    @if($secondaryHref && $secondaryLabel)
        <a href="{{ $secondaryHref }}" class="btn-secondary">{{ $secondaryLabel }}</a>
    @endif
</div>

