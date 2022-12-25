<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\SongsByUserController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\YoutubeController;
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
Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);

    Route::get('youtube/{user_id}', [YoutubeController::class, 'show']);
    Route::post('youtube', [YoutubeController::class, 'store']);
    Route::delete('youtube/{id}', [YoutubeController::class, 'destroy']);


    Route::post('songs', [SongController::class, 'store']);
    Route::delete('songs/{id}/{user_id}', [SongController::class, 'destroy']);

    Route::get('user/{user_id}/songs', [SongsByUserController::class, 'index']);
});
