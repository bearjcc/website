@php
    $title = 'Connect 4 - Ursa Minor Games';
    $description = 'Play Connect 4 online. Classic strategy game for two players. Drop pieces to get 4 in a row. Vertical, horizontal, or diagonal! Free to play!';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="game-page">
        <div class="game-breadcrumb">
            <a href="{{ route('home') }}">← Back to Games</a>
        </div>

        <livewire:games.connect4 />
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

