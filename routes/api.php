<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;
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

// User auth routes
Route::apiResource('user', UserController::class);
Route::post('login', [UserLoginController::class, 'login'])->name('login');
Route::post('logout', [UserLoginController::class, 'logout'])
    ->middleware('auth:api')->name('logout');

// Product route
Route::apiResource('product', ProductController::class);

// Tag route
Route::apiResource('tag', TagController::class)->only('index', 'show');

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
})->name('api.fallback');
