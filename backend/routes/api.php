<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use App\Http\Controllers\StitchController;

Route::middleware('web')->group(function () {
    if (! app()->environment('production')) {
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,60');
    }
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,15');
});
Route::get('/health', function () {
    DB::select('select 1');

    return response()->json(['status' => 'ok']);
});


Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    if (! app()->environment('production')) {
        Route::post('/checkout/session', [CheckoutController::class, 'session']);
    }

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('projects.pages', PageController::class);
    Route::apiResource('projects.assets', AssetController::class)->only(['index', 'store', 'destroy']);
    Route::get('/projects/{project}/assets/{asset}/file', [AssetController::class, 'file'])->name('assets.file');
    Route::post('/projects/{project}/pages/{page}/publish', [PageController::class, 'publish']);
    Route::delete('/projects/{project}/pages/{page}/publish', [PageController::class, 'unpublish']);
    Route::get('/projects/{project}/pages/{page}/versions', [PageVersionController::class, 'index']);
    Route::post('/projects/{project}/pages/{page}/versions', [PageVersionController::class, 'store']);
    Route::post('/projects/{project}/pages/{page}/versions/{version}/restore', [PageVersionController::class, 'restore']);
    Route::get('/projects/{project}/pages/{page}/export', [PageExportController::class, 'download']);
    if (config('studio.ai_enabled')) {
        Route::prefix('/projects/{project}/ai')->group(function () {
            Route::post('/generate-page', [AiController::class, 'generatePage'])->middleware('throttle:6,1,ai-page:');
            Route::post('/copy', [AiController::class, 'rewriteCopy'])->middleware('throttle:12,1,ai-copy:');
            Route::post('/seo', [AiController::class, 'seo'])->middleware('throttle:12,1,ai-seo:');
            Route::post('/stitch', [StitchController::class, 'store'])->middleware('throttle:2,5,ai-stitch:');
            Route::get('/stitch/{draft}', [StitchController::class, 'show'])->whereUuid('draft');
        });
    }
    Route::apiResource('projects.products', ProductController::class);
    Route::apiResource('projects.collections', CmsCollectionController::class);
    Route::apiResource('collections.items', CmsItemController::class);
    Route::get('/projects/{project}/form-submissions', [FormSubmissionController::class, 'index']);
    Route::post('/projects/{project}/form-submissions', [FormSubmissionController::class, 'store']);
});
