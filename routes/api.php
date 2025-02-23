<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => '{lang}'], function() {
    Route::post('register', [UsersController::class, 'register']);
    Route::post('login', [UsersController::class, 'login']);
    Route::post('logout', [UsersController::class, 'logout']);
    Route::delete('users/{id}', [UsersController::class, 'delete']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('{lang}/login', [UsersController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'getUserInfo']);

