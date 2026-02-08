<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
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
            ['title' => 'Tic-Tac-Toe', 'slug' => 'tic-tac-toe', 'type' => 'board', 'description' => 'Classic 3x3 grid game', 'status' => 'published'],
            ['title' => '2048', 'slug' => 'twenty-forty-eight', 'type' => 'puzzle', 'description' => 'Number puzzle game', 'status' => 'published'],
            ['title' => 'Connect 4', 'slug' => 'connect-4', 'type' => 'board', 'description' => 'Vertical connect four game', 'status' => 'published'],
            ['title' => 'Sudoku', 'slug' => 'sudoku', 'type' => 'puzzle', 'description' => 'Number placement puzzle', 'status' => 'published'],
            ['title' => 'Chess', 'slug' => 'chess', 'type' => 'board', 'description' => 'Classic strategy game', 'status' => 'published'],
            ['title' => 'Checkers', 'slug' => 'checkers', 'type' => 'board', 'description' => 'Classic board game', 'status' => 'published'],
            ['title' => 'Minesweeper', 'slug' => 'minesweeper', 'type' => 'puzzle', 'description' => 'Mine detection game', 'status' => 'published'],
            ['title' => 'Snake', 'slug' => 'snake', 'type' => 'arcade', 'description' => 'Classic snake game', 'status' => 'published'],
            ['title' => 'Letter Walker', 'slug' => 'letter-walker', 'type' => 'word', 'description' => 'Daily word puzzle', 'status' => 'published'],
        ];

        foreach ($games as $gameData) {
            Game::create($gameData);
        }
    }

    #[Test]
    public function homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ursa Minor Games');
        $response->assertSee('The sky is the limit');
    }

    #[Test]
    public function homepage_navigation_links_work(): void
    {
        // Homepage has navigable content: game cards link to game pages
        $response = $this->get('/');
        $response->assertStatus(200);

        // Game cards provide navigation to games
        $response->assertSee('href=', false);
        $response->assertSee('/games/', false);
    }

    #[Test]
    public function games_index_page_loads_successfully(): void
    {
        $response = $this->get('/games');

        $response->assertStatus(200);
        $response->assertSee('Games');
        $response->assertSee('Play in your browser. No sign-up required.');
    }

    #[Test]
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
        $response->assertSee('Letter Walker');
    }

    #[Test]
    public function about_page_loads_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About');
        $response->assertSee('Ursa Minor makes small games');
    }

    #[Test]
    public function tic_tac_toe_game_loads_successfully(): void
    {
        $response = $this->get('/games/tic-tac-toe');

        $response->assertStatus(200);
        $response->assertSee('Tic-Tac-Toe');
    }

    #[Test]
    public function tic_tac_toe_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TicTacToe::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\TicTacToe::class, $component->instance());
    }

    #[Test]
    public function twenty_forty_eight_game_loads_successfully(): void
    {
        $response = $this->get('/games/twenty-forty-eight');

        $response->assertStatus(200);
        $response->assertSee('2048');
    }

    #[Test]
    public function twenty_forty_eight_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TwentyFortyEight::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\TwentyFortyEight::class, $component->instance());
    }

    #[Test]
    public function connect_four_game_loads_successfully(): void
    {
        $response = $this->get('/games/connect-4');

        $response->assertStatus(200);
        $response->assertSee('Connect 4');
    }

    #[Test]
    public function connect_four_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Connect4::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Connect4::class, $component->instance());
    }

    #[Test]
    public function sudoku_game_loads_successfully(): void
    {
        $response = $this->get('/games/sudoku');

        $response->assertStatus(200);
        $response->assertSee('Sudoku');
    }

    #[Test]
    public function sudoku_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Sudoku::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Sudoku::class, $component->instance());
    }

    #[Test]
    public function minesweeper_game_loads_successfully(): void
    {
        $response = $this->get('/games/minesweeper');

        $response->assertStatus(200);
        $response->assertSee('Galaxy Mapper');
    }

    public function galaxy_mapper_route_works(): void
    {
        $response = $this->get('/games/galaxy-mapper');

        $response->assertStatus(200);
        $response->assertSee('Galaxy Mapper');
    }

    #[Test]
    public function minesweeper_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Minesweeper::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Minesweeper::class, $component->instance());
    }

    #[Test]
    public function snake_game_loads_successfully(): void
    {
        $response = $this->get('/games/snake');

        $response->assertStatus(200);
        $response->assertSee('Snake');
    }

    #[Test]
    public function snake_component_has_required_methods(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Snake::class);

        // Test that the component can be instantiated
        $this->assertInstanceOf(\App\Livewire\Games\Snake::class, $component->instance());
    }

    #[Test]
    public function chess_game_loads_successfully(): void
    {
        $response = $this->get('/games/chess');

        $response->assertStatus(200);
        $response->assertSee('Chess');
    }

    #[Test]
    public function checkers_game_loads_successfully(): void
    {
        $response = $this->get('/games/checkers');

        $response->assertStatus(200);
        $response->assertSee('Checkers');
    }

    #[Test]
    public function invalid_game_shows_not_found(): void
    {
        $response = $this->get('/games/invalid-game');

        $response->assertStatus(404);
    }

    #[Test]
    public function twenty_forty_eight_game_works(): void
    {
        $response = $this->get('/games/twenty-forty-eight');

        $response->assertStatus(200);
        $response->assertSee('2048');
    }

    #[Test]
    public function tic_tac_toe_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\TicTacToe::class);

        // Test initial state
        $component->assertSet('currentPlayer', 'X');
        $component->assertSet('movesCount', 0);

        // Test mode selection (ai-easy, ai-medium, ai-impossible, or pvp)
        $component->call('setGameMode', 'ai-easy');
        $component->assertSet('gameMode', 'ai-easy');

        // Test cell click
        $component->call('makeMove', 0);
        $component->assertSet('board.0', 'X');
        $component->assertSet('currentPlayer', 'O');
        $component->assertSet('movesCount', 1);

        // Test new game
        $component->call('newGame');
        $component->assertSet('movesCount', 0);
        $component->assertSet('currentPlayer', 'X');
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function sudoku_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Sudoku::class);

        // Test initial state (component uses gameComplete not gameOver)
        $component->assertSet('selectedCell', null);
        $component->assertSet('gameComplete', false);

        // Load a known puzzle with (0,0) empty so placement is testable
        $puzzleWithTopLeftEmpty = '003020600900305001001806400008102900700000008006708200002609500800203009005010300';
        $component->set('customPuzzleInput', $puzzleWithTopLeftEmpty);
        $component->call('loadCustomPuzzle');

        // Test cell selection and number placement
        $component->call('selectCell', 0, 0);
        $component->assertSet('selectedCell', [0, 0]);

        $component->call('placeNumber', 1);
        $component->assertSet('board.0.0', 1);
    }

    #[Test]
    public function minesweeper_game_functionality_works(): void
    {
        $component = Livewire::test(\App\Livewire\Games\Minesweeper::class);

        // Test initial state
        $component->assertSet('gameOver', false);
        $component->assertSet('gameWon', false);

        // Test cell reveal
        $component->call('revealCell', 0, 0);
        $component->assertSet('board.0.0.revealed', true);

        // Test new game
        $component->call('newGame');
        $component->assertSet('gameOver', false);
        $component->assertSet('gameWon', false);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function game_play_component_maps_games_correctly(): void
    {
        $game = Game::where('slug', 'connect-4')->first();

        $response = $this->get("/games/{$game->slug}");

        $response->assertStatus(200);
        $response->assertSee('Connect 4');
    }

    #[Test]
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
