<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return file_get_contents($indexPath);
    }
    return view('welcome');
});

// Simulation-only checkout routes — blocked in production via controller guard
Route::get('/checkout/simulate', [CheckoutController::class, 'simulateView'])->name('checkout.simulate');
Route::post('/checkout/simulate/pay', [CheckoutController::class, 'simulatePay'])->name('checkout.pay');
Route::post('/checkout/simulate/cancel', [CheckoutController::class, 'simulateCancel'])->name('checkout.cancel');

// Fallback for all other non-API routes to support Vue SPA client-side history routing
Route::fallback(function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    abort(404);
});
