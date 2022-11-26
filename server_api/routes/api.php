<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;

Route::POST('login', [AuthController::class, 'login']);
Route::POST('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::POST('logout', [AuthController::class, 'logout']);
    Route::GET('users/me', [UserController::class, 'show_me']);

    Route::GET('users', [UserController::class, 'index']);
    Route::POST('users', [UserController::class, 'create']);
    Route::GET('users/{user}', [UserController::class, 'show'])
        ->middleware('can:view,user');
    Route::PUT('users/{user}', [UserController::class, 'update'])
        ->middleware('can:update,user');
    Route::PATCH('users/{user}/password', [UserController::class, 'update_password'])
        ->middleware('can:updatePassword,user');
});
