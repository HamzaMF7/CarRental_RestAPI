<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandContoller;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryContoller;
use App\Http\Middleware\VerifyJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
  ############################# Client APIs ########################################"
*/

// Authentication APIs
Route::group(['prefix' => 'user'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/signup',  'signup');
        Route::post('/login',  'login');
        Route::post('/logout',  'logout');
        Route::get('/refresh',  'refresh');
        Route::get('/protectedResource',  'protectedResource');
    });
});



/*
############################# Amdin APIs ########################################"
*/
// Authentication APIs
Route::group(['prefix' => 'admin'], function () {
    Route::controller(AdminAuthController::class)->group(function () {
        // All other routes inside the group will be protected by authentication middleware
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::post('/register', 'register');
        Route::get('/refresh', 'refresh');
        Route::get('/protectedResource',  'protectedResource')->middleware(VerifyJWT::class);
    });
});


Route::apiResource('car', CarController::class);
Route::post('/findcar', [CarController::class, 'findCar']);
Route::apiResource('brand', BrandContoller::class);
Route::apiResource('category', CategoryContoller::class);





Route::get('/user', function (Request $request) {
    return $request->user(['message' => 'returned succesfuly']);
})->middleware('auth:sanctum');
