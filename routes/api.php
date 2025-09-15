<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

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

Route::group([
    'prefix' => 'posts/{post_id}/comments',
    'middleware' => 'auth.api'
], function () {
    Route::post('', [CommentController::class, 'store']);
});

Route::group([
    'prefix' => 'comments',
    'middleware' => 'auth.api'
], function () {
    Route::match(['patch', 'put'], '{comment_id}', [CommentController::class, 'update']);
    Route::delete('{comment_id}', [CommentController::class, 'delete']);
});
