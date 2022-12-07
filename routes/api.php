<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CityApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use App\Http\Controllers\Api\V1\UserProfilePhotoApiController;

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    // Customer Send and verify OTP
    Route::post('/send-otp', [
        AuthApiController::class, 'sendOtp'
    ])->name('send-otp');

    Route::post('/verify-otp', [
        AuthApiController::class, 'verifyOtp'
    ])->name('verify-otp');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('user/get', [UserApiController::class, 'show']);
        Route::put('user/update', [UserApiController::class, 'update']);
        Route::apiResource('user-profile-photos', UserProfilePhotoApiController::class)->only('index', 'store', 'destroy');

        Route::apiResource('cities', CityApiController::class)->only('index');
    });
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
