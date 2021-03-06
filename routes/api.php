<?php

use \App\Http\Controllers\TweetController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CommentController;
use \App\Http\Controllers\FollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function() {

    Route::get("user", [UserController::class, "user"]);
    Route::get('tweets/{id}/comments', [CommentController::class, 'showTweetComments']);
    Route::get('following', [FollowerController::class, 'index']);
    Route::get('{id}/follow', [FollowerController::class, 'follow']);
    Route::get('{id}/unfollow', [FollowerController::class, 'unfollow']);
    Route::resource('tweets',TweetController::class);
    Route::resource('comments', CommentController::class);

});
