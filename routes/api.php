<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Middleware\VerifyJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// // users APIs 
// Route::post('/signup',  'signup']);
// Route::post('login',  'login']);
// Route::post('refresh',  'refresh']);


/*
  ############################# Clients APIs ########################################"
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
############################# Dashboard APIs ########################################"
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





Route::get('/user', function (Request $request) {
    return $request->user(['message' => 'returned succesfuly']);
})->middleware('auth:sanctum');
