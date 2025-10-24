<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Sudoku\SudokuBoard;

class SudokuBoardTest extends TestCase
{
    /** @test */
    public function it_parses_string_to_board()
    {
        $str = '530070000600195000098000060800060003400803001700020006060000280000419005000080079';
        $board = SudokuBoard::fromString($str);
        
        $this->assertEquals(5, $board->getValue(0, 0));
        $this->assertEquals(3, $board->getValue(0, 1));
        $this->assertEquals(0, $board->getValue(0, 3));
    }
    
    /** @test */
    public function it_validates_valid_board()
    {
        $str = '534678912672195348198342567859761423426853791713924856961537284287419635345286179';
        $board = SudokuBoard::fromString($str);
        
        $this->assertTrue($board->isValid());
        $this->assertTrue($board->isSolved());
    }
    
    /** @test */
    public function it_detects_conflicts()
    {
        $board = new SudokuBoard();
        $board->setValue(0, 0, 5);
        $board->setValue(0, 1, 5); // Conflict in row
        
        $this->assertFalse($board->isValid());
    }
    
    /** @test */
    public function it_calculates_candidates()
    {
        $str = '530070000600195000098000060800060003400803001700020006060000280000419005000080079';
        $board = SudokuBoard::fromString($str);
        
        $candidates = $board->getCandidates(0, 2);
        
        $this->assertContains(1, $candidates);
        $this->assertContains(4, $candidates);
    }
    
    /** @test */
    public function it_handles_box_index_calculation()
    {
        $this->assertEquals(0, SudokuBoard::getBoxIndex(0, 0));
        $this->assertEquals(0, SudokuBoard::getBoxIndex(1, 2));
        $this->assertEquals(4, SudokuBoard::getBoxIndex(4, 4));
        $this->assertEquals(8, SudokuBoard::getBoxIndex(8, 8));
    }
    
    /** @test */
    public function it_converts_to_string()
    {
        $board = new SudokuBoard();
        $board->setValue(0, 0, 5);
        $board->setValue(0, 1, 3);
        
        $str = $board->toString();
        $this->assertEquals(81, strlen($str));
        $this->assertEquals('5', $str[0]);
        $this->assertEquals('3', $str[1]);
    }
    
    /** @test */
    public function it_throws_exception_for_invalid_string_length()
    {
        $this->expectException(\InvalidArgumentException::class);
        SudokuBoard::fromString('123');
    }
    
    /** @test */
    public function it_throws_exception_for_invalid_value()
    {
        $board = new SudokuBoard();
        
        $this->expectException(\InvalidArgumentException::class);
        $board->setValue(0, 0, 10);
    }
}





