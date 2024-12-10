<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;

Route::middleware(['keycloak:subscribers-role'])->group(function () {
    Route::prefix('subscribers')->group(function () {
        Route::get('/', [SubscriberController::class, 'index']);
        Route::post('/', [SubscriberController::class, 'store']);
        Route::get('/{subscriberId}', [SubscriberController::class, 'show']);
        Route::put('/{subscriberId}', [SubscriberController::class, 'update']);
        Route::delete('/{subscriberId}', [SubscriberController::class, 'destroy']);
    });
});

Route::middleware(['keycloak:subscriptions-role'])->group(function () {
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index']);
        Route::post('/', [SubscriptionController::class, 'store']);
        Route::get('/{subscriptionId}', [SubscriptionController::class, 'show']);
        Route::put('/{subscriptionId}', [SubscriptionController::class, 'update']);
        Route::delete('/{subscriptionId}', [SubscriptionController::class, 'destroy']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
