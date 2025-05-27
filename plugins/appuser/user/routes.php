<?php
use Illuminate\Support\Facades\Route;
use AppUser\User\Http\Controllers\UserController;
use AppUser\User\Http\Middleware\UserMiddleware;

Route::group([
    'prefix' => 'api/v1',
], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::group([
        'middleware' => UserMiddleware::class
    ], function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });
});