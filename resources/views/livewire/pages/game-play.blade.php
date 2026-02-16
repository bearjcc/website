@php
    $componentMap = [
        'tic-tac-toe' => 'games.tic-tac-toe',
        'sudoku' => 'games.sudoku',
        'twenty-forty-eight' => 'games.twenty-forty-eight',
        'minesweeper' => 'games.minesweeper',
        'snake' => 'games.snake',
        'connect-4' => 'games.connect4',
        'checkers' => 'games.checkers',
        'chess' => 'games.chess',
    ];
    $componentName = $componentMap[$game->slug] ?? null;
@endphp

@if(!$componentName)
    @include('livewire.pages.game-not-found')
@elseif(!$started)
    {{-- Game entry: breadcrumb, optional opponent choice, rules, Start game --}}
    <section class="section pt-24 md:pt-32 pb-16 md:pb-20">
        <div class="max-w-[960px] mx-auto">
            <nav aria-label="Breadcrumb" class="text-sm text-ink/70 mb-6">
                <a href="{{ route('games.index') }}" class="hover:text-star transition-colors">Games</a>
                <span class="mx-1" aria-hidden="true">→</span>
                <span class="text-ink font-medium">{{ $game->title }}</span>
            </nav>

            @if($game->hasOpponentChoice())
                <h2 class="h2 mb-4">Who do you want to play?</h2>
                <div class="flex flex-wrap gap-3 mb-6" role="group" aria-label="Opponent choice">
                    @foreach(['computer' => ['vs Computer', 'Play against the game'], 'friend' => ['vs Friend', 'Two players, same device'], 'solo' => ['Solo', 'Practice, no opponent']] as $key => $label)
                        <button type="button"
                                wire:click="$set('mode', '{{ $key }}')"
                                class="px-5 py-3 rounded-lg border-2 text-left transition-colors min-h-[44px] {{ $mode === $key ? 'border-star bg-star/10 text-ink' : 'border-ink/20 text-ink/80 hover:border-ink/40 hover:text-ink' }}">
                            <span class="font-medium block">{{ $label[0] }}</span>
                            <span class="text-sm opacity-80">{{ $label[1] }}</span>
                        </button>
                    @endforeach
                </div>
                @if($game->slug === 'tic-tac-toe' && in_array($mode, ['computer', 'solo'], true))
                    <div class="mb-6">
                        <p class="text-sm font-medium text-ink/80 mb-2">Choose your symbol</p>
                        <div class="flex gap-3">
                            <button type="button"
                                    wire:click="$set('playerSymbol', 'X')"
                                    class="px-4 py-2 rounded-lg border-2 transition-colors {{ $playerSymbol === 'X' ? 'border-star bg-star/10 text-ink' : 'border-ink/20 text-ink/80 hover:border-ink/40' }}">
                                Moon (X)
                            </button>
                            <button type="button"
                                    wire:click="$set('playerSymbol', 'O')"
                                    class="px-4 py-2 rounded-lg border-2 transition-colors {{ $playerSymbol === 'O' ? 'border-star bg-star/10 text-ink' : 'border-ink/20 text-ink/80 hover:border-ink/40' }}">
                                Star (O)
                            </button>
                        </div>
                    </div>
                @endif
            @endif

            @if($game->rules_md)
                <div class="mb-8" x-data="{ showRules: false }">
                    <button type="button"
                            @click="showRules = !showRules"
                            class="text-sm font-medium text-ink/80 hover:text-star transition-colors"
                            :aria-expanded="showRules">
                        Rules
                    </button>
                    <div x-show="showRules"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="mt-3 text-sm text-ink/70 prose prose-invert prose-sm max-w-none">
                        {!! \Illuminate\Support\Str::markdown($game->rules_md) !!}
                    </div>
                </div>
            @endif

            <div>
                <button type="button" wire:click="startGame" class="btn-primary">
                    Start game
                </button>
            </div>
        </div>
    </section>
@else
    @php
        $childProps = ['game' => $game];
        if ($game->slug === 'tic-tac-toe') {
            $childProps['initialGameMode'] = $this->resolvedGameMode();
            $childProps['initialPlayerSymbol'] = $playerSymbol;
        }
    @endphp
    @livewire($componentName, $childProps, key('game-' . $game->slug . '-' . $game->id))
@endif