<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;




Route::group(['prefix' => '{lang}'], function () {
    Route::post('register', [UsersController::class, 'register']);
    Route::post('login', [UsersController::class, 'login']);
    Route::post('logout', [UsersController::class, 'logout']);
    /* Route::delete('users/{id}', [UsersController::class, 'delete']); */
    Route::middleware('auth:sanctum')->post('update-username', [UsersController::class, 'updateUserName']);
    Route::middleware('auth:sanctum')->post('update-password', [UsersController::class, 'updatePassword']);
    Route::middleware('auth:sanctum')->get('getUserList', [UsersController::class, 'getUserList']);
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

/* Route::post('{lang}/login', [UsersController::class, 'login']); */
Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'getUserInfo']);
Route::middleware('auth:sanctum')->put('users/{email}/upgrade', [UsersController::class, 'upgradeToAdmin']);
Route::middleware('auth:sanctum')->put('users/{email}/downgrade', [UsersController::class, 'downgradeFromAdmin']);
Route::middleware('auth:sanctum')->post('checkoldpassword', [UsersController::class, 'checkOldPassword']);
Route::middleware('auth:sanctum')->get('getUserByEmail/{email}', [UsersController::class, 'getUserByEmail']);




Route::get('check-booking', [BookingController::class, 'checkBooking']);
Route::post('check-booking/store', [BookingController::class, 'storeBooking']);
Route::get('check-booking/booked-dates', [BookingController::class, 'getBookedDates']);


Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::post('/delivery', [DeliveryController::class, 'storeDelivery']);
Route::get('/user-bookings', [BookingController::class, 'getUserBookings']);
Route::get('/bookings', [BookingController::class, 'getBookings']);
Route::get('/all-bookings', [BookingController::class, 'getAllBookings']);
Route::get('/bookings/{id}', [BookingController::class, 'getBookingById']);
Route::middleware('auth:sanctum')->delete('/bookings/{id}', [BookingController::class, 'deleteBooking']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cartdelete/{id}', [CartController::class, 'destroy']);
    Route::post('/cart/decrease/{productId}', [CartController::class, 'decreaseQuantity']);
    Route::middleware('auth:sanctum')->put('/bookings/{id}', [BookingController::class, 'updateBooking'])->name('bookings.update');
    

});
