<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)->only(['index', 'update']);
Route::post('products/subscribe', [ProductController::class, 'subscribe'])->name('products.subscribe');

Route::get('subscriber/verify/{token}', [SubscriberController::class, 'verifyEmail'])->name('subscribers.verify');
Route::get('subscriber/resend-email-verification', [SubscriberController::class, 'resendEmailVerification'])->name('subscribers.resendEmailVerification');
