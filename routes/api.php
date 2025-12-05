<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/me', [AuthController::class, 'me']);
});

// Products Routes

Route::apiResource('products', ProductController::class)->middleware('auth:api');

Route::get('dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats'])->middleware('auth:api');

// Orders Routes

Route::get('orders', [OrderController::class, 'index'])->middleware('auth:api');
Route::get('orders/{id}', [OrderController::class, 'show'])->middleware('auth:api');
Route::post('orders', [OrderController::class, 'store'])->middleware('auth:api');
