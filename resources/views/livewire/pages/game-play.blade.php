<div>
    @php
        // Map game slug to Livewire component
        $componentMap = [
            'tic-tac-toe' => 'games.tic-tac-toe',
            'sudoku' => 'games.sudoku',
            'minesweeper' => 'games.minesweeper',
            'connect-4' => 'games.connect4',
            'snake' => 'games.snake',
            'twenty-forty-eight' => 'games.twenty-forty-eight',
        ];
        
        $component = $componentMap[$game->slug] ?? null;
    @endphp

    @if($component)
        <livewire:dynamic-component :component="$component" />
    @else
        {{-- Fallback for games not yet implemented --}}
        <div class="section py-20 text-center">
            <x-heroicon-o-wrench class="w-12 h-12 mx-auto text-ink/40 mb-4" />
            <h1 class="h2 text-ink mb-2">{{ $game->title }}</h1>
            @if($game->short_description)
                <p class="p mb-6">{{ $game->short_description }}</p>
            @endif
            <p class="p text-ink/60">This constellation is still taking shape.</p>
            <div class="mt-8">
                <a href="{{ route('games.index') }}" class="btn-secondary">
                    {{ __('ui.back_to_games') }}
                </a>
            </div>
        </div>
    @endif
</div>
