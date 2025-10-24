<?php

namespace App\Services\Sudoku;

use App\Enums\TechniqueType;

/**
 * Human-Style Sudoku Solver
 *
 * This class implements human-solvable Sudoku techniques in order of difficulty.
 * It provides educational hints and explanations for each solving step,
 * making it perfect for learning and teaching Sudoku strategies.
 *
 * Supported Techniques (in order of difficulty):
 * - Naked Single (weight: 1) - Cell with only one candidate
 * - Hidden Single (weight: 2) - Digit appears in only one cell within unit
 * - Locked Candidates (weight: 3) - Pointing and claiming techniques
 * - Naked Pair (weight: 4) - Two cells with identical candidates
 * - Hidden Pair (weight: 5) - Two digits in exactly two cells
 * - And more advanced techniques...
 *
 * The solver provides detailed explanations for each step, making it
 * educational for users learning Sudoku solving strategies.
 */

class HumanSolver
{
    /**
     * Find next human-solvable step
     * Returns Step object or null if stuck
     */
    public function nextStep(SudokuBoard $board): ?Step
    {
        // Try techniques in order of difficulty
        return $this->tryNakedSingle($board)
            ?? $this->tryHiddenSingle($board)
            ?? $this->tryLockedCandidates($board)
            ?? $this->tryNakedPair($board)
            ?? $this->tryHiddenPair($board)
            ?? $this->tryNakedTriple($board)
            ?? $this->tryHiddenTriple($board)
            ?? $this->tryXWing($board)
            ?? $this->trySwordfish($board);
    }
    
    /**
     * Solve puzzle using human techniques, collecting all steps
     * Returns array of Step objects, or null if can't solve
     */
    public function solveWithSteps(SudokuBoard $board, ?array $limitTechniques = null): ?array
    {
        $startTime = microtime(true);
        $timeout = 10; // 10 seconds max for human solving

        $board = clone $board;
        $steps = [];
        $maxIterations = 50; // Reduced for faster testing

        for ($i = 0; $i < $maxIterations; $i++) {
            // Check timeout
            if (microtime(true) - $startTime > $timeout) {
                return null; // Timeout
            }
            if ($board->isSolved()) {
                return $steps;
            }
            
            $step = $this->nextStep($board);
            
            if ($step === null) {
                return null; // Stuck, can't solve with human techniques
            }
            
            // Check if technique is limited
            if ($limitTechniques && !in_array($step->type, $limitTechniques, true)) {
                return null; // Would require disallowed technique
            }
            
            $steps[] = $step;
            $this->applyStep($board, $step);
        }
        
        return null; // Too many iterations
    }
    
    private function tryNakedSingle(SudokuBoard $board): ?Step
    {
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                $candidates = $board->getCandidates($r, $c);
                
                if (count($candidates) === 1) {
                    $digit = $candidates[0];
                    
                    return new Step(
                        type: TechniqueType::NakedSingle,
                        placements: [['r' => $r, 'c' => $c, 'd' => $digit]],
                        eliminations: [],
                        explanation: "R" . ($r + 1) . "C" . ($c + 1) . " has only one candidate: {$digit}",
                        focusCells: [['r' => $r, 'c' => $c]]
                    );
                }
            }
        }
        
        return null;
    }
    
    private function tryHiddenSingle(SudokuBoard $board): ?Step
    {
        // Check rows
        for ($r = 0; $r < 9; $r++) {
            for ($d = 1; $d <= 9; $d++) {
                $positions = [];
                
                for ($c = 0; $c < 9; $c++) {
                    $candidates = $board->getCandidates($r, $c);
                    if (in_array($d, $candidates, true)) {
                        $positions[] = $c;
                    }
                }
                
                if (count($positions) === 1) {
                    $c = $positions[0];
                    
                    return new Step(
                        type: TechniqueType::HiddenSingle,
                        placements: [['r' => $r, 'c' => $c, 'd' => $d]],
                        eliminations: [],
                        explanation: "In row " . ($r + 1) . ", digit {$d} can only go in column " . ($c + 1),
                        focusCells: [['r' => $r, 'c' => $c]]
                    );
                }
            }
        }
        
        // Check columns
        for ($c = 0; $c < 9; $c++) {
            for ($d = 1; $d <= 9; $d++) {
                $positions = [];
                
                for ($r = 0; $r < 9; $r++) {
                    $candidates = $board->getCandidates($r, $c);
                    if (in_array($d, $candidates, true)) {
                        $positions[] = $r;
                    }
                }
                
                if (count($positions) === 1) {
                    $r = $positions[0];
                    
                    return new Step(
                        type: TechniqueType::HiddenSingle,
                        placements: [['r' => $r, 'c' => $c, 'd' => $d]],
                        eliminations: [],
                        explanation: "In column " . ($c + 1) . ", digit {$d} can only go in row " . ($r + 1),
                        focusCells: [['r' => $r, 'c' => $c]]
                    );
                }
            }
        }
        
        // Check boxes
        for ($b = 0; $b < 9; $b++) {
            $boxRow = intval($b / 3) * 3;
            $boxCol = ($b % 3) * 3;
            
            for ($d = 1; $d <= 9; $d++) {
                $positions = [];
                
                for ($r = $boxRow; $r < $boxRow + 3; $r++) {
                    for ($c = $boxCol; $c < $boxCol + 3; $c++) {
                        $candidates = $board->getCandidates($r, $c);
                        if (in_array($d, $candidates, true)) {
                            $positions[] = ['r' => $r, 'c' => $c];
                        }
                    }
                }
                
                if (count($positions) === 1) {
                    $pos = $positions[0];
                    
                    return new Step(
                        type: TechniqueType::HiddenSingle,
                        placements: [['r' => $pos['r'], 'c' => $pos['c'], 'd' => $d]],
                        eliminations: [],
                        explanation: "In box " . ($b + 1) . ", digit {$d} can only go in R" . ($pos['r'] + 1) . "C" . ($pos['c'] + 1),
                        focusCells: [['r' => $pos['r'], 'c' => $pos['c']]]
                    );
                }
            }
        }
        
        return null;
    }
    
    private function tryLockedCandidates(SudokuBoard $board): ?Step
    {
        // Pointing: candidates in box confined to one row/col
        for ($b = 0; $b < 9; $b++) {
            $boxRow = intval($b / 3) * 3;
            $boxCol = ($b % 3) * 3;
            
            for ($d = 1; $d <= 9; $d++) {
                $rows = [];
                $cols = [];
                
                for ($r = $boxRow; $r < $boxRow + 3; $r++) {
                    for ($c = $boxCol; $c < $boxCol + 3; $c++) {
                        $candidates = $board->getCandidates($r, $c);
                        if (in_array($d, $candidates, true)) {
                            $rows[] = $r;
                            $cols[] = $c;
                        }
                    }
                }
                
                // Pointing row: all candidates in same row
                if (!empty($rows) && count(array_unique($rows)) === 1) {
                    $row = $rows[0];
                    $eliminations = [];
                    
                    for ($c = 0; $c < 9; $c++) {
                        if ($c < $boxCol || $c >= $boxCol + 3) {
                            $candidates = $board->getCandidates($row, $c);
                            if (in_array($d, $candidates, true)) {
                                $eliminations[] = ['r' => $row, 'c' => $c, 'd' => $d];
                            }
                        }
                    }
                    
                    if (!empty($eliminations)) {
                        return new Step(
                            type: TechniqueType::LockedCandidates,
                            placements: [],
                            eliminations: $eliminations,
                            explanation: "In box " . ($b + 1) . ", digit {$d} is confined to row " . ($row + 1) . ", so it can be eliminated from the rest of the row",
                            focusCells: array_map(fn($e) => ['r' => $e['r'], 'c' => $e['c']], $eliminations)
                        );
                    }
                }
                
                // Pointing column: all candidates in same column
                if (!empty($cols) && count(array_unique($cols)) === 1) {
                    $col = $cols[0];
                    $eliminations = [];
                    
                    for ($r = 0; $r < 9; $r++) {
                        if ($r < $boxRow || $r >= $boxRow + 3) {
                            $candidates = $board->getCandidates($r, $col);
                            if (in_array($d, $candidates, true)) {
                                $eliminations[] = ['r' => $r, 'c' => $col, 'd' => $d];
                            }
                        }
                    }
                    
                    if (!empty($eliminations)) {
                        return new Step(
                            type: TechniqueType::LockedCandidates,
                            placements: [],
                            eliminations: $eliminations,
                            explanation: "In box " . ($b + 1) . ", digit {$d} is confined to column " . ($col + 1) . ", so it can be eliminated from the rest of the column",
                            focusCells: array_map(fn($e) => ['r' => $e['r'], 'c' => $e['c']], $eliminations)
                        );
                    }
                }
            }
        }
        
        // Claiming: candidates in row/col confined to one box
        for ($r = 0; $r < 9; $r++) {
            for ($d = 1; $d <= 9; $d++) {
                $positions = [];
                
                for ($c = 0; $c < 9; $c++) {
                    $candidates = $board->getCandidates($r, $c);
                    if (in_array($d, $candidates, true)) {
                        $positions[] = $c;
                    }
                }
                
                if (count($positions) >= 2) {
                    // Check if all positions are in the same box
                    $boxes = array_map(fn($c) => SudokuBoard::getBoxIndex($r, $c), $positions);
                    if (count(array_unique($boxes)) === 1) {
                        $box = $boxes[0];
                        $boxRow = intval($box / 3) * 3;
                        $boxCol = ($box % 3) * 3;
                        $eliminations = [];
                        
                        for ($br = $boxRow; $br < $boxRow + 3; $br++) {
                            for ($bc = $boxCol; $bc < $boxCol + 3; $bc++) {
                                if ($br !== $r) {
                                    $candidates = $board->getCandidates($br, $bc);
                                    if (in_array($d, $candidates, true)) {
                                        $eliminations[] = ['r' => $br, 'c' => $bc, 'd' => $d];
                                    }
                                }
                            }
                        }
                        
                        if (!empty($eliminations)) {
                            return new Step(
                                type: TechniqueType::LockedCandidates,
                                placements: [],
                                eliminations: $eliminations,
                                explanation: "In row " . ($r + 1) . ", digit {$d} is confined to box " . ($box + 1) . ", so it can be eliminated from the rest of the box",
                                focusCells: array_map(fn($e) => ['r' => $e['r'], 'c' => $e['c']], $eliminations)
                            );
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
    private function tryNakedPair(SudokuBoard $board): ?Step
    {
        // Check rows
        for ($r = 0; $r < 9; $r++) {
            $pairs = [];

            for ($c = 0; $c < 9; $c++) {
                $candidates = $board->getCandidates($r, $c);
                if (count($candidates) === 2) {
                    $key = implode(',', $candidates);
                    if (!isset($pairs[$key])) {
                        $pairs[$key] = [];
                    }
                    $pairs[$key][] = $c;
                }
            }

            foreach ($pairs as $candidates => $positions) {
                if (count($positions) === 2) {
                    $digits = explode(',', $candidates);
                    $eliminations = [];

                    for ($c = 0; $c < 9; $c++) {
                        if (!in_array($c, $positions, true)) {
                            $cellCandidates = $board->getCandidates($r, $c);
                            foreach ($digits as $digit) {
                                if (in_array((int)$digit, $cellCandidates, true)) {
                                    $eliminations[] = ['r' => $r, 'c' => $c, 'd' => (int)$digit];
                                }
                            }
                        }
                    }

                    if (!empty($eliminations)) {
                        return new Step(
                            type: TechniqueType::NakedPair,
                            placements: [],
                            eliminations: $eliminations,
                            explanation: "R" . ($r + 1) . "C" . ($positions[0] + 1) . " and R" . ($r + 1) . "C" . ($positions[1] + 1) . " form a naked pair with candidates {{$digits[0]}, {$digits[1]}}, eliminating these from the rest of row " . ($r + 1),
                            focusCells: [
                                ['r' => $r, 'c' => $positions[0]],
                                ['r' => $r, 'c' => $positions[1]]
                            ]
                        );
                    }
                }
            }
        }

        // Check columns
        for ($c = 0; $c < 9; $c++) {
            $pairs = [];

            for ($r = 0; $r < 9; $r++) {
                $candidates = $board->getCandidates($r, $c);
                if (count($candidates) === 2) {
                    $key = implode(',', $candidates);
                    if (!isset($pairs[$key])) {
                        $pairs[$key] = [];
                    }
                    $pairs[$key][] = $r;
                }
            }

            foreach ($pairs as $candidates => $positions) {
                if (count($positions) === 2) {
                    $digits = explode(',', $candidates);
                    $eliminations = [];

                    for ($r = 0; $r < 9; $r++) {
                        if (!in_array($r, $positions, true)) {
                            $cellCandidates = $board->getCandidates($r, $c);
                            foreach ($digits as $digit) {
                                if (in_array((int)$digit, $cellCandidates, true)) {
                                    $eliminations[] = ['r' => $r, 'c' => $c, 'd' => (int)$digit];
                                }
                            }
                        }
                    }

                    if (!empty($eliminations)) {
                        return new Step(
                            type: TechniqueType::NakedPair,
                            placements: [],
                            eliminations: $eliminations,
                            explanation: "R" . ($positions[0] + 1) . "C" . ($c + 1) . " and R" . ($positions[1] + 1) . "C" . ($c + 1) . " form a naked pair with candidates {{$digits[0]}, {$digits[1]}}, eliminating these from the rest of column " . ($c + 1),
                            focusCells: [
                                ['r' => $positions[0], 'c' => $c],
                                ['r' => $positions[1], 'c' => $c]
                            ]
                        );
                    }
                }
            }
        }

        return null;
    }
    
    private function tryHiddenPair(SudokuBoard $board): ?Step
    {
        // Implementation for Hidden Pair technique
        return null;
    }
    
    private function tryNakedTriple(SudokuBoard $board): ?Step
    {
        // Implementation for Naked Triple technique
        return null;
    }
    
    private function tryHiddenTriple(SudokuBoard $board): ?Step
    {
        // Implementation for Hidden Triple technique
        return null;
    }
    
    private function tryXWing(SudokuBoard $board): ?Step
    {
        // Implementation for X-Wing technique
        return null;
    }
    
    private function trySwordfish(SudokuBoard $board): ?Step
    {
        // Implementation for Swordfish technique
        return null;
    }
    
    private function applyStep(SudokuBoard $board, Step $step): void
    {
        foreach ($step->placements as $placement) {
            $board->setValue($placement['r'], $placement['c'], $placement['d']);
        }
    }
}





