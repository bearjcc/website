<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Pages\About;
use App\Livewire\Pages\AdminFeatures;
use App\Livewire\Pages\GamePlay;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\LoreEdit;
use App\Livewire\Pages\LoreIndex;
use App\Livewire\Pages\LoreShow;
use App\Models\Game;
use Illuminate\Support\Facades\Route;

// Health check for Railway deployment
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('health');

// Public routes
Route::get('/', Home::class)->name('home');
Route::get('/about', About::class)->name('about');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Games index (must be before /{game:slug} so /games is not matched as a slug)
Route::get('/games', \App\Livewire\Pages\GamesIndex::class)->name('games.index');

// Top-level game routes (canonical URLs) - lazy load with starfield placeholder
Route::get('/tic-tac-toe', \App\Livewire\Games\TicTacToe::class)->name('games.tic-tac-toe')->lazy();
Route::get('/connect-4', \App\Livewire\Games\Connect4::class)->name('games.connect-4')->lazy();
Route::get('/sudoku', \App\Livewire\Games\Sudoku::class)->name('games.sudoku')->lazy();
Route::get('/twenty-forty-eight', \App\Livewire\Games\TwentyFortyEight::class)->name('games.twenty-forty-eight')->lazy();
Route::get('/minesweeper', \App\Livewire\Games\Minesweeper::class)->name('games.minesweeper')->lazy();
Route::get('/snake', \App\Livewire\Games\Snake::class)->name('games.snake')->lazy();
Route::get('/checkers', \App\Livewire\Games\Checkers::class)->name('games.checkers')->lazy();
Route::get('/chess', \App\Livewire\Games\Chess::class)->name('games.chess')->lazy();
Route::view('/letter-walker', 'games.letter-walker')->name('games.letter-walker');
Route::get('/{game:slug}', GamePlay::class)->name('games.play');

// Legacy /games/* redirects (301 to top-level)
Route::prefix('games')->group(function () {
    $legacyRedirects = [
        'tic-tac-toe', 'connect-4', 'sudoku', 'twenty-forty-eight',
        'minesweeper', 'snake', 'checkers', 'chess', 'letter-walker',
    ];
    foreach ($legacyRedirects as $slug) {
        Route::get('/'.$slug, function () use ($slug) {
            return redirect('/'.$slug, 301);
        });
    }
    Route::get('/{game:slug}', function (Game $game) {
        return redirect('/'.$game->slug, 301);
    });
});

// Sudoku API routes
Route::prefix('api/sudoku')->name('api.sudoku.')->group(function () {
    Route::post('/generate', [\App\Http\Controllers\SudokuController::class, 'generate'])->name('generate');
    Route::post('/solve', [\App\Http\Controllers\SudokuController::class, 'solve'])->name('solve');
    Route::post('/validate', [\App\Http\Controllers\SudokuController::class, 'validate'])->name('validate');
    Route::post('/hint', [\App\Http\Controllers\SudokuController::class, 'hint'])->name('hint');
    Route::post('/rate', [\App\Http\Controllers\SudokuController::class, 'rate'])->name('rate');
});

// Letter Walker API routes
Route::prefix('api/letter-walker')->name('api.letter-walker.')->group(function () {
    Route::post('/score', [\App\Http\Controllers\LetterWalkerScoreController::class, 'store'])->name('score.submit');
    Route::get('/scores', [\App\Http\Controllers\LetterWalkerScoreController::class, 'index'])->name('scores.index');
    Route::get('/scores/daily', [\App\Http\Controllers\LetterWalkerScoreController::class, 'daily'])->name('scores.daily');
});

// Blog routes removed - blog section not needed

// Contributor routes (Lore section - invisible to guests)
Route::middleware(['auth', 'can:access-lore'])->prefix('lore')->name('lore.')->group(function () {
    Route::get('/', LoreIndex::class)->name('index');
    Route::get('/create', LoreEdit::class)->name('create');
    Route::get('/{lorePage:slug}', LoreShow::class)->name('show');
    Route::get('/{lorePage:slug}/edit', LoreEdit::class)->name('edit');
});

// Admin routes
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/features', AdminFeatures::class)->name('features');
});

// Fallback for lore routes when not authenticated
Route::get('/lore/{any?}', function () {
    abort(404);
})->where('any', '.*')->middleware('guest');
