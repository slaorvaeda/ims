<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarcodeApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/scan-dispatch', [BarcodeApiController::class, 'scanDispatch']);
    Route::post('/scan-cancel', [BarcodeApiController::class, 'scanCancel']);
    Route::get('/portal-vendors', [BarcodeApiController::class, 'getPortalVendors']);
});
