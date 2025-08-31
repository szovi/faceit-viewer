<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Faceit\FaceitController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    Route::get('/faceit/player/stats', [FaceitController::class, 'getPlayerStats']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
