<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\OrderController;

Route::POST('login', [AuthController::class, 'loginUser']);
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

    Route::GET('orders', [OrderController::class, 'index']);
    Route::GET('orders/{order}', [OrderController::class, 'show']);

    Route::PATCH('orders/{order}/{user}/accepted', [OrderController::class, 'update_accepted']);
    Route::PATCH('orders/{order}/{user}/delivered', [OrderController::class, 'update_delivered']);
    Route::PATCH('orders/{order}/status', [OrderController::class, 'update_status']);
});
