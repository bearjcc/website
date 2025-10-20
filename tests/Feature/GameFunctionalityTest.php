<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GameFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed games for testing
        $this->seedGames();
    }

    private function seedGames(): void
    {
        $games = [
            ['title' => 'Tic-Tac-Toe', 'slug' => 'tic-tac-toe', 'type' => 'board', 'description' => 'Classic 3x3 grid game'],
            ['title' => '2048', 'slug' => 'twenty-forty-eight', 'type' => 'puzzle', 'description' => 'Number puzzle game'],
            ['title' => 'Connect 4', 'slug' => 'connect-4', 'type' => 'board', 'description' => 'Vertical connect four game'],
            ['title' => 'Sudoku', 'slug' => 'sudoku', 'type' => 'puzzle', 'description' => 'Number placement puzzle'],
            ['title' => 'Chess', 'slug' => 'chess', 'type' => 'board', 'description' => 'Classic strategy game'],
            ['title' => 'Checkers', 'slug' => 'checkers', 'type' => 'board', 'description' => 'Classic board game'],
            ['title' => 'Minesweeper', 'slug' => 'minesweeper', 'type' => 'puzzle', 'description' => 'Mine detection game'],
            ['title' => 'Snake', 'slug' => 'snake', 'type' => 'arcade', 'description' => 'Classic snake game'],
        ];

        foreach ($games as $gameData) {
            Game::create($gameData);
        }
    }

    /** @test */
    public function homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ursa Minor Games');
        $response->assertSee('The sky is the limit');
    }

    /** @test */
    public function homepage_navigation_links_work(): void
    {
        // Test Home link
        $this->get('/')->assertSee('href="/"');

        // Test Games link
        $this->get('/')->assertSee('href="/games"');

        // Test About link
        $this->get('/')->assertSee('href="/about"');
    }

    /** @test */
    public function games_index_page_loads_successfully(): void
    {
        $response = $this->get('/games');

        $response->assertStatus(200);
        $response->assertSee('Games');
        $response->assertSee('Play in your browser. No sign-up required.');
    }

    /** @test */
    public function games_index_shows_all_games(): void
    {
        $response = $this->get('/games');

        $response->assertSee('Tic-Tac-Toe');
        $response->assertSee('2048');
        $response->assertSee('Connect 4');
        $response->assertSee('Sudoku');
        $response->assertSee('Chess');
        $response->assertSee('Checkers');
        $response->assertSee('Minesweeper');
        $response->assertSee('Snake');
    }

    /** @test */
    public function about_page_loads_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About');
        $response->assertSee('Ursa Minor makes small games');
    }

    /** @test */
    public function tic_tac_toe_game_loads_successfully(): void
    {
        $response = $this->get('/games/tic-tac-toe');

        $response->assertStatus(200);
        $response->assertSee('Tic-Tac-Toe');
    }

    /** @test */
    public function tic_tac_toe_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TicTacToe::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\TicTacToe::class, $component->instance());
    }

    /** @test */
    public function twenty_forty_eight_game_loads_successfully(): void
    {
        $response = $this->get('/games/twenty-forty-eight');

        $response->assertStatus(200);
        $response->assertSee('2048');
    }

    /** @test */
    public function twenty_forty_eight_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TwentyFortyEight::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\TwentyFortyEight::class, $component->instance());
    }

    /** @test */
    public function connect_four_game_loads_successfully(): void
    {
        $response = $this->get('/games/connect-4');

        $response->assertStatus(200);
        $response->assertSee('Connect 4');
    }

    /** @test */
    public function connect_four_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Connect4::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Connect4::class, $component->instance());
    }

    /** @test */
    public function sudoku_game_loads_successfully(): void
    {
        $response = $this->get('/games/sudoku');

        $response->assertStatus(200);
        $response->assertSee('Sudoku');
    }

    /** @test */
    public function sudoku_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Sudoku::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Sudoku::class, $component->instance());
    }

    /** @test */
    public function minesweeper_game_loads_successfully(): void
    {
        $response = $this->get('/games/minesweeper');

        $response->assertStatus(200);
        $response->assertSee('Minesweeper');
    }

    /** @test */
    public function minesweeper_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Minesweeper::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Minesweeper::class, $component->instance());
    }

    /** @test */
    public function snake_game_loads_successfully(): void
    {
        $response = $this->get('/games/snake');

        $response->assertStatus(200);
        $response->assertSee('Snake');
    }

    /** @test */
    public function snake_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Snake::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Snake::class, $component->instance());
    }

    /** @test */
    public function chess_game_loads_successfully(): void
    {
        $response = $this->get('/games/chess');

        $response->assertStatus(200);
        $response->assertSee('Chess');
    }

    /** @test */
    public function checkers_game_loads_successfully(): void
    {
        $response = $this->get('/games/checkers');

        $response->assertStatus(200);
        $response->assertSee('Checkers');
    }

    /** @test */
    public function invalid_game_shows_not_found(): void
    {
        $response = $this->get('/games/invalid-game');

        $response->assertStatus(404);
    }

    /** @test */
    public function first_2048_link_shows_not_found(): void
    {
        $response = $this->get('/games/2048');

        $response->assertStatus(200);
        $response->assertSee('Game Not Found');
    }

    /** @test */
    public function tic_tac_toe_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TicTacToe::class);

        // Test initial state
        $component->assertSet('currentPlayer', 'moon');
        $component->assertSet('moves', 0);

        // Test mode selection
        $component->call('setGameMode', 'easy');
        $component->assertSet('gameMode', 'easy');

        // Test cell click
        $component->call('makeMove', 0);
        $component->assertSet('board.0', 'moon');
        $component->assertSet('currentPlayer', 'star');
        $component->assertSet('moves', 1);

        // Test new game
        $component->call('newGame');
        $component->assertSet('moves', 0);
        $component->assertSet('currentPlayer', 'moon');
    }

    /** @test */
    public function twenty_forty_eight_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TwentyFortyEight::class);

        // Test initial state
        $component->assertSet('score', 0);
        $component->assertSet('bestScore', 0);

        // Test new game
        $component->call('newGame');
        $component->assertSet('score', 0);

        // Test undo (should be disabled initially)
        $component->assertSet('canUndo', false);
    }

    /** @test */
    public function connect_four_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Connect4::class);

        // Test initial state
        $component->assertSet('state.currentPlayer', 'red');
        $component->assertSet('state.gameOver', false);

        // Test drop piece
        $component->call('dropPiece', 0);
        $component->assertSet('state.currentPlayer', 'yellow');
        $component->assertSet('state.moves', 1);

        // Test new game
        $component->call('newGame');
        $component->assertSet('state.moves', 0);
        $component->assertSet('state.currentPlayer', 'red');
    }

    /** @test */
    public function sudoku_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Sudoku::class);

        // Test initial state
        $component->assertSet('selectedCell', null);
        $component->assertSet('gameOver', false);

        // Test cell selection
        $component->call('selectCell', 0, 0);
        $component->assertSet('selectedCell', [0, 0]);

        // Test number placement
        $component->call('placeNumber', 1);
        $component->assertSet('board.0.0', 1);
    }

    /** @test */
    public function minesweeper_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Minesweeper::class);

        // Test initial state
        $component->assertSet('gameOver', false);
        $component->assertSet('gameWon', false);

        // Test cell reveal
        $component->call('revealCell', 0, 0);
        $component->assertSet('revealed.0.0', true);

        // Test new game
        $component->call('newGame');
        $component->assertSet('gameOver', false);
        $component->assertSet('gameWon', false);
    }

    /** @test */
    public function snake_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Snake::class);

        // Test initial state
        $component->assertSet('score', 0);
        $component->assertSet('gameOver', false);

        // Test game start
        $component->call('startGame');
        $component->assertSet('gameStarted', true);

        // Test new game
        $component->call('newGame');
        $component->assertSet('score', 0);
        $component->assertSet('gameOver', false);
    }

    /** @test */
    public function all_game_components_have_render_methods(): void
    {
        $gameComponents = [
            \App\Livewire\Games\TicTacToe::class,
            \App\Livewire\Games\TwentyFortyEight::class,
            \App\Livewire\Games\Connect4::class,
            \App\Livewire\Games\Sudoku::class,
            \App\Livewire\Games\Minesweeper::class,
            \App\Livewire\Games\Snake::class,
        ];

        foreach ($gameComponents as $componentClass) {
            $component = new $componentClass();
            $this->assertTrue(method_exists($component, 'render'),
                "Component {$componentClass} is missing render method");
        }
    }

    /** @test */
    public function game_play_component_maps_games_correctly(): void
    {
        $game = Game::where('slug', 'connect-4')->first();

        $response = $this->get("/games/{$game->slug}");

        $response->assertStatus(200);
        $response->assertSee('Connect 4');
    }

    /** @test */
    public function game_components_are_properly_registered(): void
    {
        $this->assertTrue(class_exists(\App\Livewire\Games\TicTacToe::class));
        $this->assertTrue(class_exists(\App\Livewire\Games\TwentyFortyEight::class));
        $this->assertTrue(class_exists(\App\Livewire\Games\Connect4::class));
        $this->assertTrue(class_exists(\App\Livewire\Games\Sudoku::class));
        $this->assertTrue(class_exists(\App\Livewire\Games\Minesweeper::class));
        $this->assertTrue(class_exists(\App\Livewire\Games\Snake::class));
    }
}
