<?php

use Illuminate\Support\Facades\Route;
use AppChat\Reaction\Http\Controllers\ReactionController;
use AppUser\User\Http\Middleware\UserMiddleware;
use AppUser\User\Models\User;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => UserMiddleware::class
], function () {
    Route::get('/available_emojis', [ReactionController::class, 'getAvailableEmojis']);
    Route::post('/react/{message_id}', [ReactionController::class, 'reactToMessage']);
});
