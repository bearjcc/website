@php
    $title = '2048 - Ursa Minor Games';
    $description = 'Play 2048 online. Slide and combine tiles to reach 2048. Use arrow keys or WASD. Free to play, no registration required.';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="game-page">
        <div class="game-breadcrumb">
            <a href="{{ route('home') }}">← Back to Games</a>
        </div>

        <livewire:games.twenty-forty-eight />
    </section>

    <style>
        .game-page {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .game-breadcrumb {
            margin-bottom: 2rem;
        }

        .game-breadcrumb a {
            color: var(--color-star-yellow, #fff89a);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .game-breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</x-layouts.app>

