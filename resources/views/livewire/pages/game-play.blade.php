@php
    // Map game slug to Livewire component class
    $componentMap = [
        'tic-tac-toe' => 'games.tic-tac-toe',
        'sudoku' => 'games.sudoku',
        'twenty-forty-eight' => 'games.twenty-forty-eight',
        'minesweeper' => 'games.minesweeper',
        'snake' => 'games.snake',
        'connect-4' => 'games.connect4',
    ];

    $componentName = $componentMap[$game->slug] ?? null;
@endphp

@if($componentName)
    @livewire($componentName, ['game' => $game], key('game-' . $game->slug . '-' . $game->id))
@else
    @include('livewire.pages.game-not-found')
@endif