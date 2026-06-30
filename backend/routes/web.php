<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

// Simulation-only checkout routes — blocked in production via controller guard
Route::get('/checkout/simulate', [CheckoutController::class, 'simulateView'])->name('checkout.simulate');
Route::post('/checkout/simulate/pay', [CheckoutController::class, 'simulatePay'])->name('checkout.pay');
Route::post('/checkout/simulate/cancel', [CheckoutController::class, 'simulateCancel'])->name('checkout.cancel');
