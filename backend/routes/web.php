<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublishedPageController;

Route::get('/', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath, ['Cache-Control' => 'private, no-store']);
    }
    return view('welcome');
});

// Simulation-only checkout routes — blocked in production via controller guard
Route::get('/checkout/simulate', [CheckoutController::class, 'simulateView'])->name('checkout.simulate');
Route::post('/checkout/simulate/pay', [CheckoutController::class, 'simulatePay'])->name('checkout.pay');
Route::post('/checkout/simulate/cancel', [CheckoutController::class, 'simulateCancel'])->name('checkout.cancel');

Route::get('/p/{publicId}', [PublishedPageController::class, 'show'])
    ->whereUuid('publicId')
    ->withoutMiddleware('web');
Route::get('/studio-published/images/{hash}.{extension}', [PublishedPageController::class, 'image'])
    ->where(['hash' => '[a-f0-9]{64}', 'extension' => 'jpg|png|gif|webp']);
Route::get('/images/{file}', [PublishedPageController::class, 'bundledImage'])
    ->where('file', '[A-Za-z0-9_-]+\.(?:jpe?g|png|gif|webp)');

Route::get('/error/{status}', fn (string $status) => abort((int) $status))
    ->whereIn('status', ['403', '404', '500', '503']);

$workspace = function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath, ['Cache-Control' => 'private, no-store']);
    }
    abort(404);
};

$routes = 'login|register|account|demo|showcase|welcome|about|privacy|terms|docs|editor/\d+/\d+|(?:inventory|cms|templates|settings)(?:/\d+)?';
Route::get('/{workspacePath}', fn () => $workspace())->where('workspacePath', $routes);
Route::fallback(fn () => abort(404));
