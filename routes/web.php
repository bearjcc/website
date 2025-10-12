<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Games
Route::prefix('games')->name('games.')->group(function () {
    Route::get('/', function () {
        return view('pages.games.index');
    })->name('index');
    
    Route::get('/tic-tac-toe', function () {
        return view('pages.games.tic-tac-toe');
    })->name('tic-tac-toe');
    
    Route::get('/sudoku', function () {
        return view('pages.games.sudoku');
    })->name('sudoku');
});
