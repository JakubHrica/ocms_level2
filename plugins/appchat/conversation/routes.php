<?php

use Illuminate\Support\Facades\Route;
use AppChat\Conversation\Http\Controllers\ConversationController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => UserMiddleware::class
], function () {
    Route::get('/search_users/{email}', [ConversationController::class, 'searchUsers']);
        Route::post('/start_conversation/{user_id}', [ConversationController::class, 'startConversation']);
        Route::post('/change_conversation_name/{conversation_id}', [ConversationController::class, 'changeConversationName']);
});