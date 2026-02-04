<?php

namespace App\Http\Controllers;

use App\Models\LetterWalkerScore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterWalkerScoreController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'moves' => 'required|integer|min:0',
            'words_found' => 'required|integer|min:0',
            'puzzle_number' => 'required|integer|min:1',
            'player_name' => 'nullable|string|max:50',
        ]);

        $score = LetterWalkerScore::create([
            'user_id' => Auth::id(),
            'player_name' => $validated['player_name'] ?? 'Anonymous',
            'score' => $validated['score'],
            'moves' => $validated['moves'],
            'words_found' => $validated['words_found'],
            'puzzle_number' => $validated['puzzle_number'],
            'date_played' => now()->toDateString(),
        ]);

        return response()->json([
            'success' => true,
            'score' => $score,
        ]);
    }

    public function index(): JsonResponse
    {
        $scores = LetterWalkerScore::with('user')
            ->orderBy('score', 'desc')
            ->orderBy('date_played', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'scores' => $scores,
        ]);
    }

    public function daily(): JsonResponse
    {
        $scores = LetterWalkerScore::with('user')
            ->todaysPuzzle()
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'scores' => $scores,
            'date' => now()->toDateString(),
        ]);
    }
}
