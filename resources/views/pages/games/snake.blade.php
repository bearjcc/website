@php
    $title = 'Snake - Ursa Minor Games';
    $description = 'Play Snake online. Classic arcade game with keyboard controls. Eat food to grow, avoid walls and yourself. Free to play!';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="game-page">
        <div class="game-breadcrumb">
            <a href="{{ route('games.index') }}">← Back to Games</a>
        </div>

        <livewire:games.snake />
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

