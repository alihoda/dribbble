<?php

use App\Http\Controllers\SocialNetworkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Social Network Routes
|--------------------------------------------------------------------------
|
| Here is where you can register social network routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::name('social.')->group(function () {

    Route::get('user/{user}', [SocialNetworkController::class, 'index'])->name('index');
    Route::get('{socialNetwork}', [SocialNetworkController::class, 'show'])->name('show');

    // Routes that need authenticated user
    Route::middleware('auth:api')->group(function () {

        Route::post('store', [SocialNetworkController::class, 'store'])->name('store');
        Route::put('{socialNetwork}', [SocialNetworkController::class, 'update'])->name('update');
        Route::delete('{socialNetwork}', [SocialNetworkController::class, 'destroy'])->name('destroy');
    });
});

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
})->name('social.fallback');
