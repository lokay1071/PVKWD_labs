<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;

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

Route::prefix('subscribers')->group(function () {
    Route::get('/', [SubscriberController::class, 'index']);          // Отримати список всіх підписників
    Route::post('/', [SubscriberController::class, 'store']);         // Створити нового підписника
    Route::get('/{subscriberId}', [SubscriberController::class, 'show']); // Отримати інформацію про конкретного підписника
    Route::put('/{subscriberId}', [SubscriberController::class, 'update']); // Оновити дані підписника
    Route::delete('/{subscriberId}', [SubscriberController::class, 'destroy']); // Видалити підписника
});

Route::prefix('subscriptions')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index']);          // Отримати список всіх підписок
    Route::post('/', [SubscriptionController::class, 'store']);         // Створити нову підписку
    Route::get('/{subscriptionId}', [SubscriptionController::class, 'show']); // Отримати інформацію про конкретну підписку
    Route::put('/{subscriptionId}', [SubscriptionController::class, 'update']); // Оновити дані підписки
    Route::delete('/{subscriptionId}', [SubscriptionController::class, 'destroy']); // Видалити підписку
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
