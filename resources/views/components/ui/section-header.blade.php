@props([
    'kicker' => null,
    'title' => null,
    'subtitle' => null,
])

<div class="section text-center space-y-2">
    @if($kicker)
        <p class="kicker">{{ $kicker }}</p>
    @endif

    @if($title)
        <h2 class="h2">{{ $title }}</h2>
    @endif

    @if($subtitle)
        <p class="p max-w-prose mx-auto">{{ $subtitle }}</p>
    @endif
</div>

