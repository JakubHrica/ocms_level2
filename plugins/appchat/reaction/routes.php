<?php

use Illuminate\Support\Facades\Route;
use AppChat\Reaction\Http\Controllers\ReactionController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::prefix('/api')->middleware(UserMiddleware::class)->group(function () {
    Route::middleware(UserMiddleware::class)->group(function () {
        // Route::post('reactions/react', ReactionController::class, 'reactToMessage');
    });
});
