<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Pages\About;
use App\Livewire\Pages\AdminFeatures;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\LoreEdit;
use App\Livewire\Pages\LoreIndex;
use App\Livewire\Pages\LoreShow;
use App\Models\Game;
use Illuminate\Support\Facades\Route;

$standalonePlayRedirectSlugs = Game::standalonePlayRedirectSlugs();
$legacyRedirects = array_unique(array_merge(Game::knownSlugs(), ['2048']));
$gamesBaseUrl = rtrim((string) config('services.games.base_url', 'https://ursaminor.games'), '/');
$gamesRedirectUrl = static fn (string $path = ''): string => $gamesBaseUrl.'/'.ltrim($path, '/');

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

// Small games: 301 to apex path URLs, e.g. ursaminor.games/sudoku (see GAMES_BASE_URL). Taverns RPG: separate host (taverns.ursaminor.games).
Route::get('/games', fn () => redirect()->away($gamesRedirectUrl(), 301))->name('games.index');

// All game show/play pages redirect to the configured games base URL (path on that origin, not a games. subdomain).
foreach ($standalonePlayRedirectSlugs as $slug) {
    Route::get('/'.$slug, fn () => redirect()->away($gamesRedirectUrl($slug), 301));
}

// Keep /play redirects so old shared links remain valid.
foreach ($standalonePlayRedirectSlugs as $slug) {
    Route::get('/'.$slug.'/play', fn () => redirect()->away($gamesRedirectUrl($slug), 301));
}

// Catch any known game slug and redirect to canonical URL.
Route::get('/{game:slug}', fn (Game $game) => redirect()->away($gamesRedirectUrl($game->slug), 301))->name('games.show');
Route::get('/{game:slug}/play', fn (Game $game) => redirect()->away($gamesRedirectUrl($game->slug), 301))->name('games.play');

// Legacy /games/* redirects (301 to game page or play URL)
Route::prefix('games')->group(function () use ($legacyRedirects) {
    $gamesBaseUrl = rtrim((string) config('services.games.base_url', 'https://ursaminor.games'), '/');
    $gamesRedirectUrl = static fn (string $path = ''): string => $gamesBaseUrl.'/'.ltrim($path, '/');

    foreach ($legacyRedirects as $slug) {
        Route::get('/'.$slug, function () use ($slug, $gamesRedirectUrl) {
            return redirect()->away($gamesRedirectUrl($slug), 301);
        });
        Route::get('/'.$slug.'/play', function () use ($slug, $gamesRedirectUrl) {
            return redirect()->away($gamesRedirectUrl($slug), 301);
        });
    }
    Route::get('/{game:slug}', function (Game $game) use ($gamesRedirectUrl) {
        return redirect()->away($gamesRedirectUrl($game->slug), 301);
    });
    Route::get('/{game:slug}/play', function (Game $game) use ($gamesRedirectUrl) {
        return redirect()->away($gamesRedirectUrl($game->slug), 301);
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
