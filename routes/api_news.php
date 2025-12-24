<?php

use App\Http\Controllers\Api\NewsApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| News API Routes
|--------------------------------------------------------------------------
|
| All routes are protected with API key middleware
| Add header: X-API-Key: your-api-key-here
| Or query parameter: ?api_key=your-api-key-here
|
*/

Route::middleware(['api.key'])->prefix('v1')->group(function () {
    
    // Home endpoint - Get all data for home screen
    Route::get('/home', [NewsApiController::class, 'home']);
    
    // Articles endpoints
    Route::get('/articles', [NewsApiController::class, 'index']);
    Route::get('/articles/latest', [NewsApiController::class, 'latest']);
    Route::get('/articles/popular', [NewsApiController::class, 'popular']);
    Route::get('/articles/trending', [NewsApiController::class, 'trending']);
    Route::get('/articles/featured', [NewsApiController::class, 'featured']);
    Route::get('/articles/search', [NewsApiController::class, 'search']);
    Route::get('/articles/{slug}', [NewsApiController::class, 'show']);
    Route::get('/articles/id/{id}', [NewsApiController::class, 'showById']);
    Route::get('/articles/{id}/related', [NewsApiController::class, 'related']);
    
    // Category endpoints
    Route::get('/categories', [NewsApiController::class, 'categories']);
    Route::get('/categories/{slug}/articles', [NewsApiController::class, 'byCategory']);
    
    // Statistics endpoint
    Route::get('/stats', [NewsApiController::class, 'stats']);
    
});

// Public test endpoint (no API key required)
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString(),
        'version' => 'v1.0.0',
    ]);
});
