<?php

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

// User crud routes
Route::name('user.')->prefix('user')->group(function () {

    Route::get('', [UserController::class, 'index'])->name('index');
    Route::get('{user}', [UserController::class, 'show'])->name('show');

    Route::middleware('auth:api')->group(function () {
        Route::put('{user}', [UserController::class, 'update'])->name('update');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

// User auth routes
Route::post('register', [UserController::class, 'store'])->name('register');
Route::post('login', [UserLoginController::class, 'login'])->name('login');
Route::post('logout', [UserLoginController::class, 'logout'])
    ->middleware('auth:api')->name('logout');

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
})->name('api.fallback');
