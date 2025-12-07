<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HighScoreController;
use App\Http\Controllers\InfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('game.index');

Route::get('/scores', [HighScoreController::class, 'index'])->name('scores.index');
Route::post('/scores', [HighScoreController::class, 'store'])->name('scores.store');

Route::get('/info', [InfoController::class, 'index'])->name('info.index');
