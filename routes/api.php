<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::group([
    'prefix' => 'users'
], function () {
    Route::post('', [UserController::class, 'store']);
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('login');
    Route::post('/verify', [AuthController::class, 'verifyCode']);
});

Route::group([
    'prefix' => 'posts',
    'middleware' => 'auth.api'
], function () {
    Route::get('', [PostController::class, 'index']);
    Route::get('{post_id}', [PostController::class, 'get']);
    Route::post('', [PostController::class, 'store']);
});
