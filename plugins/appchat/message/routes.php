<?php
use Illuminate\Support\Facades\Route;
use AppChat\Message\Http\Controllers\MessageController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::prefix('/api')->middleware(UserMiddleware::class)->group(function () {
    Route::middleware(UserMiddleware::class)->group(function () {
        Route::post('/send_message', [MessageController::class, 'sendMessage']);
    });
});