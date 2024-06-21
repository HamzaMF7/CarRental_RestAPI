<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandContoller;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryContoller;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
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


// Cars APIs
Route::apiResource('car', CarController::class);
Route::post('/findcar', [CarController::class, 'findCar']);
Route::post('/filter', [CarController::class, 'applyFilters']);
Route::get('/cars/price-range', [CarController::class, 'priceRange']);
// Brands APIs
Route::apiResource('brand', BrandContoller::class);
// Categories APIs
Route::apiResource('category', CategoryContoller::class);
// Rentals APIs
Route::apiResource('rental', RentalController::class);
Route::post('/rental/checkout', [RentalController::class, 'store']);
// Payments APIs
Route::apiResource('payment', PaymentController::class);
// Reviews APIs
Route::apiResource('review', ReviewController::class);
Route::post('/review/{carID}', [ReviewController::class, 'carReviews']);







Route::get('/user', function (Request $request) {
    return $request->user(['message' => 'returned succesfuly']);
})->middleware('auth:sanctum');
