@props([
    'kicker' => null,
    'title' => null,
    'subtitle' => null,
])

<div class="section text-center">
    @if($kicker)
        <p class="kicker mb-2">{{ $kicker }}</p>
    @endif

    @if($title)
        <h2 class="h2 mb-3">{{ $title }}</h2>
    @endif

    @if($subtitle)
        <p class="p max-w-prose mx-auto text-left md:text-center">{{ $subtitle }}</p>
    @endif
</div>

