<?php

namespace App\Http\Controllers;

use App\Enums\Difficulty;
use App\Services\Sudoku\DifficultyRating;
use App\Services\Sudoku\HumanSolver;
use App\Services\Sudoku\SudokuBoard;
use App\Services\Sudoku\SudokuGenerator;
use App\Services\Sudoku\SudokuSolver;
use Illuminate\Http\Request;

class SudokuController extends Controller
{
    public function index()
    {
        return view('sudoku.index');
    }

    public function generate(Request $request)
    {
        $difficulty = Difficulty::from($request->input('difficulty', 'medium'));

        $generator = new SudokuGenerator();
        $result = $generator->generate($difficulty);

        return response()->json([
            'puzzle' => $result['puzzle']->toString(),
            'solution' => $result['solution']->toString(),
            'difficulty' => $difficulty->value,
            'rating' => $result['rating']['totalWeight'],
            'band' => $result['rating']['band']->value,
            'seed' => $result['seed'],
        ]);
    }

    public function solve(Request $request)
    {
        $puzzleStr = $request->input('puzzle');
        $board = SudokuBoard::fromString($puzzleStr);

        $solver = new SudokuSolver();
        $solution = $solver->solve($board);

        if (! $solution) {
            return response()->json(['error' => 'No solution found'], 400);
        }

        return response()->json([
            'solution' => $solution->toString(),
        ]);
    }

    public function validate(Request $request)
    {
        $puzzleStr = $request->input('puzzle');
        $board = SudokuBoard::fromString($puzzleStr);

        $report = $board->getValidationReport();

        return response()->json([
            'valid' => $report['isValid'],
            'solved' => $board->isSolved(),
            'errors' => $report['errors'],
            'warnings' => $report['warnings'],
            'clueCount' => 81 - substr_count($puzzleStr, '.'),
        ]);
    }

    public function hint(Request $request)
    {
        $puzzleStr = $request->input('puzzle');
        $board = SudokuBoard::fromString($puzzleStr);

        $humanSolver = new HumanSolver();
        $step = $humanSolver->nextStep($board);

        if (! $step) {
            return response()->json(['message' => 'No hints available'], 404);
        }

        return response()->json([
            'technique' => $step->type->value,
            'explanation' => $step->explanation,
            'placements' => $step->placements,
            'eliminations' => $step->eliminations,
            'focusCells' => $step->focusCells,
            'weight' => $step->weight,
        ]);
    }

    public function rate(Request $request)
    {
        $puzzleStr = $request->input('puzzle');
        $board = SudokuBoard::fromString($puzzleStr);

        $rating = new DifficultyRating();
        $ratingData = $rating->rate($board);

        return response()->json([
            'totalWeight' => $ratingData['totalWeight'],
            'band' => $ratingData['band']->value,
            'solvable' => $ratingData['solvable'],
            'steps' => $ratingData['steps'],
        ]);
    }
}
