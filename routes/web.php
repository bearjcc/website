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

// Games routes (public) - lazy load game pages with starfield placeholder
Route::prefix('games')->name('games.')->group(function () {
    Route::get('/', \App\Livewire\Pages\GamesIndex::class)->name('index');
    Route::get('/tic-tac-toe', \App\Livewire\Games\TicTacToe::class)->name('tic-tac-toe')->lazy();
    Route::get('/connect-4', \App\Livewire\Games\Connect4::class)->name('connect-4')->lazy();
    Route::get('/sudoku', \App\Livewire\Games\Sudoku::class)->name('sudoku')->lazy();
    Route::get('/twenty-forty-eight', \App\Livewire\Games\TwentyFortyEight::class)->name('twenty-forty-eight')->lazy();
    Route::get('/minesweeper', \App\Livewire\Games\Minesweeper::class)->name('minesweeper')->lazy();
    Route::get('/snake', \App\Livewire\Games\Snake::class)->name('snake')->lazy();
    Route::get('/checkers', \App\Livewire\Games\Checkers::class)->name('checkers')->lazy();
    Route::get('/chess', \App\Livewire\Games\Chess::class)->name('chess')->lazy();
    Route::view('/letter-walker', 'games.letter-walker')->name('letter-walker');
    Route::get('/{game:slug}', GamePlay::class)->name('play');
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
