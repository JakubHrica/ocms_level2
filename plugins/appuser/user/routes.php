<?php
use Illuminate\Support\Facades\Route;
use AppUser\User\Http\Controllers\UserController;
use AppUser\User\Http\Middleware\UserMiddleware;

/* REVIEW - Tip - Môžeš použiť aj takýto zápis

Route::group([
    'prefix' => 'api/v1',
    'middleware' => []
], function () {
    ...
})

*/

Route::prefix('/api/v1')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);   
    Route::middleware(UserMiddleware::class)->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
