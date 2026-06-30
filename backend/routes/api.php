<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CmsCollectionController;
use App\Http\Controllers\CmsItemController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\PageExportController;
use App\Http\Controllers\PageVersionController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,60');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,15');
Route::get('/health', fn() => response()->json(['status' => 'ok', 'ts' => now()]));


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/checkout/session', [CheckoutController::class, 'session']);

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('projects.pages', PageController::class);
    Route::apiResource('projects.assets', AssetController::class)->only(['index', 'store', 'destroy']);
    Route::get('/projects/{project}/pages/{page}/versions', [PageVersionController::class, 'index']);
    Route::post('/projects/{project}/pages/{page}/versions', [PageVersionController::class, 'store']);
    Route::post('/projects/{project}/pages/{page}/versions/{version}/restore', [PageVersionController::class, 'restore']);
    Route::get('/projects/{project}/pages/{page}/export', [PageExportController::class, 'download']);
    Route::post('/projects/{project}/ai/generate-page', [AiController::class, 'generatePage']);
    Route::post('/projects/{project}/ai/copy', [AiController::class, 'rewriteCopy']);
    Route::post('/projects/{project}/ai/seo', [AiController::class, 'seo']);
    Route::apiResource('projects.products', ProductController::class);
    Route::apiResource('projects.collections', CmsCollectionController::class);
    Route::apiResource('collections.items', CmsItemController::class);
    Route::get('/projects/{project}/form-submissions', [FormSubmissionController::class, 'index']);
    Route::post('/projects/{project}/form-submissions', [FormSubmissionController::class, 'store']);
});
