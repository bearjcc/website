@php
    $title = 'Minesweeper - Ursa Minor Games';
    $description = 'Play Minesweeper online. Classic mine-finding puzzle with three difficulty levels. Left-click to reveal, right-click to flag. Free to play!';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="game-page">
        <div class="game-breadcrumb">
            <a href="{{ route('home') }}">← Back to Games</a>
        </div>

        <livewire:games.minesweeper />
    </section>

    <style>
        .game-page {
            max-width: 100%;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .game-breadcrumb {
            margin-bottom: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
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

