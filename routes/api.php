<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Resources\UserResource;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/verify', [AuthController::class, 'verify']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/driver', [DriverController::class, 'show']);
        Route::post('/driver', [DriverController::class, 'update']);

        Route::post('/trip', [TripController::class, 'store']);
        Route::get('/trip/{trip}', [TripController::class, 'show']);
        Route::patch('/trip/{trip}/accept', [TripController::class, 'accept']);
        Route::patch('/trip/{trip}/start', [TripController::class, 'start']);
        Route::patch('/trip/{trip}/end', [TripController::class, 'end']);
        Route::patch('/trip/{trip}/location', [TripController::class, 'location']);

        Route::get('/user', function (Request $request) {
            return response()->json([
                'data' => new UserResource($request->user()),
            ]);
        });
    });
});
