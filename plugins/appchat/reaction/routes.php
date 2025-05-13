<?php

use Illuminate\Support\Facades\Route;
use AppChat\Reaction\Http\Controllers\ReactionController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::prefix('/api/v1')->middleware(UserMiddleware::class)->group(function () {
    Route::middleware(UserMiddleware::class)->group(function () {
        Route::post('/react', ReactionController::class, 'reactToMessage');
    });
});
