<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// // users APIs 
// Route::post('/signup', [AuthController::class, 'signup']);
// Route::post('login', [AuthController::class, 'login']);
// Route::post('refresh', [AuthController::class, 'refresh']);


Route::group(['prefix' => 'user'], function ($router) {

    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
});




Route::get('/user', function (Request $request) {
    return $request->user(['message' => 'returned succesfuly']);
})->middleware('auth:sanctum');
