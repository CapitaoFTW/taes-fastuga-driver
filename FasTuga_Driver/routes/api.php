<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\OrderController;

Route::POST('login', [AuthController::class, 'loginUser']);
Route::POST('register', [AuthController::class, 'register']);
Route::patch('orders/{order}/{user}/accepted', [OrderController::class, 'update_accepted']);

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
    Route::GET('users/{user}/orders', [OrderController::class, 'getOrdersOfUser']);
    Route::GET('users/{user}/orders/inprogress', [OrderController::class, 'getOrdersInProgressOfUser']);
});
