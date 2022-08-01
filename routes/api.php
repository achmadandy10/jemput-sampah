<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
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

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
   Route::post('login', 'login')->name('login');
   Route::post('register', 'register')->name('register'); 
   
   Route::middleware(['auth:sanctum', 'isAuth'])->group(function () {
       Route::get('profile', 'profile')->name('profile'); 
       Route::post('logout', 'logout')->name('logout'); 
   });
});

Route::controller(OrderController::class)->middleware(['auth:sanctum', 'isAuth'])->prefix('order')->name('order')->group(function () {
    Route::post('add', 'store')->name('store');
    Route::post('approved/{id}', 'approved')->name('store');
    Route::post('rejected/{id}', 'rejected')->name('rejected');
    Route::post('finished/{id}', 'finished')->name('finished');
    Route::post('deleted/{id}', 'deleted')->name('delete');
    Route::get('', 'showAll')->name('show-all');
    Route::get('id/{id}', 'showByOrderID')->name('show-by-order-id');
    Route::get('user/{user_id}', 'showByUserID')->name('show-by-user-id');
    Route::get('status/{status}', 'showByStatus')->name('show-by-status');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
