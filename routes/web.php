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

// Games index (keeps /games as the listing page)
Route::get('/games', \App\Livewire\Pages\GamesIndex::class)->name('games.index');

// Top-level game routes (new canonical URLs)
Route::get('/tic-tac-toe', \App\Livewire\Games\TicTacToe::class)->name('games.tic-tac-toe');
Route::get('/connect-4', \App\Livewire\Games\Connect4::class)->name('games.connect-4');
Route::get('/sudoku', \App\Livewire\Games\Sudoku::class)->name('games.sudoku');
Route::get('/twenty-forty-eight', \App\Livewire\Games\TwentyFortyEight::class)->name('games.twenty-forty-eight');
Route::get('/minesweeper', \App\Livewire\Games\Minesweeper::class)->name('games.minesweeper');
Route::get('/snake', \App\Livewire\Games\Snake::class)->name('games.snake');
Route::get('/checkers', \App\Livewire\Games\Checkers::class)->name('games.checkers');
Route::get('/chess', \App\Livewire\Games\Chess::class)->name('games.chess');
Route::view('/letter-walker', 'games.letter-walker')->name('games.letter-walker');

// Dynamic game route at top level using route model binding
Route::get('/{game:slug}', GamePlay::class)->name('games.play');

// Legacy /games/... routes with permanent redirects to new top-level URLs
Route::prefix('games')->group(function () {
    Route::get('/tic-tac-toe', function () {
        return redirect('/tic-tac-toe', 301);
    });

    Route::get('/connect-4', function () {
        return redirect('/connect-4', 301);
    });

    Route::get('/sudoku', function () {
        return redirect('/sudoku', 301);
    });

    Route::get('/twenty-forty-eight', function () {
        return redirect('/twenty-forty-eight', 301);
    });

    Route::get('/minesweeper', function () {
        return redirect('/minesweeper', 301);
    });

    Route::get('/snake', function () {
        return redirect('/snake', 301);
    });

    Route::get('/checkers', function () {
        return redirect('/checkers', 301);
    });

    Route::get('/chess', function () {
        return redirect('/chess', 301);
    });

    Route::get('/letter-walker', function () {
        return redirect('/letter-walker', 301);
    });

    Route::get('/{game:slug}', function (Game $game) {
        return redirect('/'.$game->slug, 301);
    })->name('games.play.legacy');
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
