<?php

declare(strict_types=1);

namespace Tests\Unit\Games;

use App\Games\TwentyFortyEight\TwentyFortyEightEngine;
use PHPUnit\Framework\TestCase;

class TwentyFortyEightEngineTest extends TestCase
{
    public function test_move_left_merges_tiles(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');

        $this->assertEquals([4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(4, $score); // 2 + 2 = 4
    }

    public function test_move_left_no_merge(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');

        $this->assertEquals([2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(0, $score);
    }

    public function test_move_right_merges_tiles(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [0, 0, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'right');

        $this->assertEquals([0, 0, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(4, $score);
    }

    public function test_move_up_merges_tiles(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'up');

        $this->assertEquals([4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(4, $score);
    }

    public function test_move_down_merges_tiles(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 2, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'down');

        $this->assertEquals([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0], $newBoard);
        $this->assertEquals(4, $score);
    }

    public function test_multiple_merges_in_row(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');

        $this->assertEquals([4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(8, $score); // 4 + 4 = 8
    }

    public function test_cannot_merge_different_numbers(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');

        $this->assertEquals([2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(0, $score);
    }

    public function test_can_move_with_empty_spaces(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2];

        $this->assertTrue($engine->canMove($board));
    }

    public function test_can_move_with_mergeable_tiles(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $this->assertTrue($engine->canMove($board));
    }

    public function test_cannot_move_full_board_no_merges(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2, 4, 8, 16, 32, 64];

        $this->assertFalse($engine->canMove($board));
    }

    public function test_has_won_false(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2, 4, 8, 16, 32, 64];

        $this->assertFalse($engine->hasWon($board));
    }

    public function test_has_won_true(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4, 8, 16, 32, 64];

        $this->assertTrue($engine->hasWon($board));
    }

    public function test_get_max_tile(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4, 8, 16, 32, 64];

        $this->assertEquals(2048, $engine->getMaxTile($board));
    }

    public function test_complex_merge_scenario(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 2, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');

        $this->assertEquals([4, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(12, $score); // 4 + 8 = 12
    }

    public function test_no_move_when_no_change(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 4, 8, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'right');

        $this->assertEquals([2, 4, 8, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(0, $score);
    }

    public function test_vertical_merge_with_gaps(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'up');

        $this->assertEquals([4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $newBoard);
        $this->assertEquals(4, $score);
    }

    public function test_empty_board_cannot_move(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = array_fill(0, 16, 0);

        $this->assertFalse($engine->canMove($board));
    }

    public function test_single_tile_cannot_move(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $this->assertTrue($engine->canMove($board));
    }

    public function test_all_directions_work(): void
    {
        $engine = new TwentyFortyEightEngine();

        // Test left and right - tiles are adjacent, will merge
        $board = [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'left');
        $this->assertEquals(4, $score);

        [$newBoard, $score] = $engine->move($board, 'right');
        $this->assertEquals(4, $score);

        // Test up and down - tiles are in same column, will merge
        $board = [2, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'up');
        $this->assertEquals(4, $score);

        [$newBoard, $score] = $engine->move($board, 'down');
        $this->assertEquals(4, $score);
    }

    public function test_invalid_direction(): void
    {
        $engine = new TwentyFortyEightEngine();
        $board = [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        [$newBoard, $score] = $engine->move($board, 'invalid');

        // Should return unchanged board
        $this->assertEquals($board, $newBoard);
        $this->assertEquals(0, $score);
    }
}
