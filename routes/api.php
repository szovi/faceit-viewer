<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Faceit\FaceitController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {

    /** Faceit Data API  -- player stats */
    Route::get('/faceit/player/stats', [FaceitController::class, 'getPlayerStats']);
    Route::get('/faceit/player/recent-matches', [FaceitController::class, 'getPlayerRecentMatches']);
    Route::get('/faceit/elo-trend', [FaceitController::class, 'getPlayerEloTrend']);


    /** Sanctum Auth endpoints */
    Route::post('/logout', [AuthController::class, 'logout']);
});
