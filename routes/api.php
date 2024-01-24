<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
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

Route::prefix('v1')->group(function () {
    Route::prefix('login')->group(function () {
        Route::post('', [AuthController::class, 'login']);
        Route::post('verify', [AuthController::class, 'verify']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('driver')->group(function () {
            Route::get('', [DriverController::class, 'show']);
            Route::post('', [DriverController::class, 'update']);
        });

        Route::prefix('trip')->group(function () {
            Route::post('', [TripController::class, 'store']);
            Route::get('{trip}', [TripController::class, 'show']);
            Route::patch('{trip}/accept', [TripController::class, 'accept']);
            Route::patch('{trip}/start', [TripController::class, 'start']);
            Route::patch('{trip}/end', [TripController::class, 'end']);
            Route::patch('{trip}/location', [TripController::class, 'location']);
        });

        Route::prefix('user')->group(function () {
            Route::get('', [UserController::class, 'show']);
            Route::patch('', [UserController::class, 'update']);
        });
    });
});
