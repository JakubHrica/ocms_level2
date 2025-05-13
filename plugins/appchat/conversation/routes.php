<?php

use Illuminate\Support\Facades\Route;
use AppChat\Conversation\Http\Controllers\ConversationController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::prefix('/api/v1')->group(function () { 
    Route::middleware(UserMiddleware::class)->group(function () {
        Route::post('/start_conversation', [ConversationController::class, 'startConversation']);
        Route::get('/search_users', [ConversationController::class, 'searchUsers']);
    });
});