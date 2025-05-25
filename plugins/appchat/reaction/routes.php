<?php

use Illuminate\Support\Facades\Route;
use AppChat\Reaction\Http\Controllers\ReactionController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::prefix('/api/v1')->middleware(UserMiddleware::class)->group(function () {
    // REVIEW - Máš tu dva krát middleware - deje sa to aj inde
    Route::middleware(UserMiddleware::class)->group(function () {
        Route::get('/available_emojis', [ReactionController::class, 'getAvailableEmojis']);
        Route::post('/react/{message_id}', [ReactionController::class, 'reactToMessage']);
    });
});
