<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VendorApplicationController;
use App\Http\Controllers\Api\AdminVendorApplicationController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendorController;
use App\Http\Middleware\EnsureUserIsVendor;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/vendor-applications', [
        VendorApplicationController::class,
        'store',
    ]);
    Route::get('/vendor/dashboard', [
    VendorController::class,
    'dashboard',
])->middleware(EnsureUserIsVendor::class);

    // Administrator-only routes
    Route::prefix('admin')
        ->middleware(EnsureUserIsAdmin::class)
        ->group(function () {
            Route::get('/vendor-applications', [
                AdminVendorApplicationController::class,
                'index',
            ]);

            Route::post('/vendor-applications/{vendorApplication}/approve', [
                AdminVendorApplicationController::class,
                'approve',
            ]);

            Route::post('/vendor-applications/{vendorApplication}/reject', [
                AdminVendorApplicationController::class,
                'reject',
            ]);
        });
});