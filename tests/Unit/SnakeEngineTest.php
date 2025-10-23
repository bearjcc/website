<?php

declare(strict_types=1);

namespace Tests\Unit\Games;

use App\Games\Snake\SnakeEngine;
use PHPUnit\Framework\TestCase;

class SnakeEngineTest extends TestCase
{
    public function test_new_game_initializes_correctly(): void
    {
        $state = SnakeEngine::newGame();

        $this->assertArrayHasKey('snake', $state);
        $this->assertArrayHasKey('direction', $state);
        $this->assertArrayHasKey('nextDirection', $state);
        $this->assertArrayHasKey('food', $state);
        $this->assertArrayHasKey('score', $state);
        $this->assertArrayHasKey('gameOver', $state);
        $this->assertArrayHasKey('gameStarted', $state);
        $this->assertArrayHasKey('speed', $state);
        $this->assertArrayHasKey('level', $state);
        $this->assertArrayHasKey('foodEaten', $state);
        $this->assertArrayHasKey('highScore', $state);
        $this->assertArrayHasKey('gameTime', $state);
        $this->assertArrayHasKey('paused', $state);

        $this->assertFalse($state['gameOver']);
        $this->assertFalse($state['gameStarted']);
        $this->assertFalse($state['paused']);
        $this->assertEquals('right', $state['direction']);
        $this->assertEquals('right', $state['nextDirection']);
        $this->assertEquals(0, $state['score']);
        $this->assertEquals(1, $state['level']);
        $this->assertEquals(150, $state['speed']);
        $this->assertCount(3, $state['snake']); // Initial snake has 3 segments
    }

    public function test_change_direction_valid(): void
    {
        $state = SnakeEngine::newGame();

        $newState = SnakeEngine::changeDirection($state, 'up');

        $this->assertEquals('up', $newState['nextDirection']);
        $this->assertEquals('right', $newState['direction']); // Current direction unchanged until tick
    }

    public function test_change_direction_prevents_opposite(): void
    {
        $state = SnakeEngine::newGame();
        $state['direction'] = 'right';

        $newState = SnakeEngine::changeDirection($state, 'left'); // Opposite direction

        $this->assertEquals('right', $newState['nextDirection']); // Should not change
    }

    public function test_game_tick_moves_snake(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;
        $originalHead = $state['snake'][0];

        $newState = SnakeEngine::gameTick($state);

        $this->assertNotEquals($originalHead, $newState['snake'][0]); // Head should move
        $this->assertCount(3, $newState['snake']); // Snake length unchanged
        $this->assertEquals(1, $newState['moveCount'] ?? 0);
    }

    public function test_game_tick_eats_food(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;

        // Position food where snake head will be after moving right
        $state['snake'][0] = ['x' => 9, 'y' => 7]; // Move head left one
        $state['snake'][1] = ['x' => 8, 'y' => 7];
        $state['snake'][2] = ['x' => 7, 'y' => 7];
        $state['food'] = ['x' => 10, 'y' => 7]; // Food to the right

        $newState = SnakeEngine::gameTick($state);

        $this->assertCount(4, $newState['snake']); // Snake grew
        $this->assertEquals(10, $newState['score']); // Score increased
        $this->assertEquals(1, $newState['foodEaten']); // Food eaten count
        $this->assertNotEquals($state['food'], $newState['food']); // Food moved
    }

    public function test_game_tick_levels_up(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;
        $state['foodEaten'] = 4; // Almost at level up (every 5 food)
        $state['speed'] = 150; // Initial speed

        // Position to eat food
        $state['snake'][0] = ['x' => 9, 'y' => 7];
        $state['snake'][1] = ['x' => 8, 'y' => 7];
        $state['snake'][2] = ['x' => 7, 'y' => 7];
        $state['food'] = ['x' => 10, 'y' => 7];

        $newState = SnakeEngine::gameTick($state);

        $this->assertEquals(2, $newState['level']); // Level increased
        $this->assertEquals(140, $newState['speed']); // Speed increased (faster)
        $this->assertEquals(5, $newState['foodEaten']); // Food eaten count
    }

    public function test_game_tick_wall_collision(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;

        // Position snake at right edge
        $state['snake'][0] = ['x' => 19, 'y' => 7]; // Right edge
        $state['snake'][1] = ['x' => 18, 'y' => 7];
        $state['snake'][2] = ['x' => 17, 'y' => 7];

        $newState = SnakeEngine::gameTick($state);

        $this->assertTrue($newState['gameOver']);
    }

    public function test_game_tick_self_collision(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;

        // Create a snake that will collide with itself when moving right
        // Snake: [(10,7), (9,7), (8,7)] moving right -> new head at (11,7)
        // But place a body segment at (11,7) to cause collision
        $state['snake'] = [
            ['x' => 10, 'y' => 7], // Head
            ['x' => 9, 'y' => 7],  // Body
            ['x' => 8, 'y' => 7],  // Body
            ['x' => 11, 'y' => 7], // Body segment where head will move - collision!
        ];

        $newState = SnakeEngine::gameTick($state);

        $this->assertTrue($newState['gameOver']);
    }

    public function test_game_tick_does_not_move_when_paused(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;
        $state['paused'] = true;
        $originalHead = $state['snake'][0];

        $newState = SnakeEngine::gameTick($state);

        $this->assertEquals($originalHead, $newState['snake'][0]); // No movement
    }

    public function test_game_tick_does_not_move_when_game_over(): void
    {
        $state = SnakeEngine::newGame();
        $state['gameStarted'] = true;
        $state['gameOver'] = true;
        $originalHead = $state['snake'][0];

        $newState = SnakeEngine::gameTick($state);

        $this->assertEquals($originalHead, $newState['snake'][0]); // No movement
    }

    public function test_food_generation_avoids_snake(): void
    {
        $state = SnakeEngine::newGame();
        $snake = $state['snake'];

        // Check that food is not on snake body
        $foodOnSnake = false;
        foreach ($snake as $segment) {
            if ($segment['x'] === $state['food']['x'] && $segment['y'] === $state['food']['y']) {
                $foodOnSnake = true;
                break;
            }
        }

        $this->assertFalse($foodOnSnake, 'Food should not be generated on snake body');
    }

    public function test_validate_move_accepts_valid_actions(): void
    {
        // Test change_direction with fresh game
        $state = SnakeEngine::newGame();
        $this->assertTrue(SnakeEngine::validateMove($state, ['action' => 'change_direction', 'direction' => 'up']));

        // Test start_game with fresh game (not started, not over)
        $this->assertTrue(SnakeEngine::validateMove($state, ['action' => 'start_game']));

        // Test tick with game started (not over, not paused)
        $state['gameStarted'] = true;
        $this->assertTrue(SnakeEngine::validateMove($state, ['action' => 'tick']));
    }

    public function test_validate_move_rejects_invalid_actions(): void
    {
        $state = SnakeEngine::newGame();

        $this->assertFalse(SnakeEngine::validateMove($state, ['action' => 'invalid']));
        $this->assertFalse(SnakeEngine::validateMove($state, ['action' => 'change_direction'])); // Missing direction
        $this->assertFalse(SnakeEngine::validateMove($state, ['action' => 'change_direction', 'direction' => 'invalid']));
    }

    public function test_opposite_directions(): void
    {
        $this->assertTrue(SnakeEngine::isOppositeDirection('up', 'down'));
        $this->assertTrue(SnakeEngine::isOppositeDirection('down', 'up'));
        $this->assertTrue(SnakeEngine::isOppositeDirection('left', 'right'));
        $this->assertTrue(SnakeEngine::isOppositeDirection('right', 'left'));

        $this->assertFalse(SnakeEngine::isOppositeDirection('up', 'left'));
        $this->assertFalse(SnakeEngine::isOppositeDirection('down', 'right'));
        $this->assertFalse(SnakeEngine::isOppositeDirection('up', 'up'));
    }

    public function test_collision_detection(): void
    {
        $snake = [
            ['x' => 10, 'y' => 7],
            ['x' => 9, 'y' => 7],
            ['x' => 8, 'y' => 7],
        ];

        // Test wall collision
        $this->assertTrue(SnakeEngine::checkCollision(['x' => -1, 'y' => 7], $snake)); // Left wall
        $this->assertTrue(SnakeEngine::checkCollision(['x' => 20, 'y' => 7], $snake)); // Right wall
        $this->assertTrue(SnakeEngine::checkCollision(['x' => 10, 'y' => -1], $snake)); // Top wall
        $this->assertTrue(SnakeEngine::checkCollision(['x' => 10, 'y' => 15], $snake)); // Bottom wall

        // Test self collision
        $this->assertTrue(SnakeEngine::checkCollision(['x' => 10, 'y' => 7], $snake)); // Head on body
        $this->assertTrue(SnakeEngine::checkCollision(['x' => 9, 'y' => 7], $snake)); // Head on body

        // Test no collision
        $this->assertFalse(SnakeEngine::checkCollision(['x' => 11, 'y' => 7], $snake)); // Empty space
        $this->assertFalse(SnakeEngine::checkCollision(['x' => 10, 'y' => 8], $snake)); // Empty space
    }

    public function test_next_head_position(): void
    {
        $head = ['x' => 10, 'y' => 7];

        $this->assertEquals(['x' => 10, 'y' => 6], SnakeEngine::getNextHeadPosition($head, 'up'));
        $this->assertEquals(['x' => 10, 'y' => 8], SnakeEngine::getNextHeadPosition($head, 'down'));
        $this->assertEquals(['x' => 9, 'y' => 7], SnakeEngine::getNextHeadPosition($head, 'left'));
        $this->assertEquals(['x' => 11, 'y' => 7], SnakeEngine::getNextHeadPosition($head, 'right'));
    }

    public function test_game_state_methods(): void
    {
        $state = SnakeEngine::newGame();

        $this->assertFalse(SnakeEngine::isGameOver($state));
        $this->assertEquals(0, SnakeEngine::calculateScore($state));
        $this->assertEquals($state['snake'], SnakeEngine::getSnakePosition($state));
        $this->assertEquals($state['food'], SnakeEngine::getFoodPosition($state));
        $this->assertEquals($state['speed'], SnakeEngine::getGameSpeed($state));
    }

    public function test_apply_move_actions(): void
    {
        $state = SnakeEngine::newGame();

        // Test start game
        $newState = SnakeEngine::applyMove($state, ['action' => 'start_game']);
        $this->assertTrue($newState['gameStarted']);

        // Test pause game
        $newState = SnakeEngine::applyMove($newState, ['action' => 'pause_game']);
        $this->assertTrue($newState['paused']);

        // Test resume game
        $newState = SnakeEngine::applyMove($newState, ['action' => 'resume_game']);
        $this->assertFalse($newState['paused']);

        // Test new game
        $newState = SnakeEngine::applyMove($newState, ['action' => 'new_game']);
        $this->assertFalse($newState['gameStarted']);
        $this->assertFalse($newState['gameOver']);
    }
}
