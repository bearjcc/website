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

// Games routes (public)
Route::prefix('games')->name('games.')->group(function () {
    Route::get('/', \App\Livewire\Pages\GamesIndex::class)->name('index');
    Route::get('/tic-tac-toe', \App\Livewire\Games\TicTacToe::class)->name('tic-tac-toe');
    Route::get('/connect-4', \App\Livewire\Games\Connect4::class)->name('connect-4');
    Route::get('/sudoku', \App\Livewire\Games\Sudoku::class)->name('sudoku');
    Route::get('/twenty-forty-eight', \App\Livewire\Games\TwentyFortyEight::class)->name('twenty-forty-eight');
    Route::get('/minesweeper', \App\Livewire\Games\Minesweeper::class)->name('minesweeper');
    Route::get('/snake', \App\Livewire\Games\Snake::class)->name('snake');
    Route::get('/checkers', \App\Livewire\Games\Checkers::class)->name('checkers');
    Route::get('/chess', \App\Livewire\Games\Chess::class)->name('chess');
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
