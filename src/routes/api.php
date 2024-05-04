<?php

use App\Http\Controllers\Api\V1\CompleteTaskController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\InterestsController;
use App\Http\Controllers\Api\V1\HuntedStoreController;
use App\Http\Controllers\Api\V1\StatisticsController;
use App\Http\Controllers\Api\V1\ConfigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){  
    
    // Rotas para User
    Route::apiResource('users', UserController::class);

    // Rotas para Item
    Route::apiResource('items', ItemController::class);

    // Rotas para Store
    Route::apiResource('stores', StoreController::class);

    // Rotas para Feedback
    Route::apiResource('feedbacks', FeedbackController::class);

    // Rotas para Purchase
    Route::apiResource('purchases', PurchaseController::class);

    // Rotas para Location
    Route::apiResource('locations', LocationController::class);

    // Rotas para Interests
    Route::apiResource('interests', InterestsController::class);

    // Rotas para HuntedStore
    Route::apiResource('huntedstores', HuntedStoreController::class);

    // Rotas para Statistics
    Route::apiResource('statistics', StatisticsController::class);

    // Rotas para Config
    Route::apiResource('configs', ConfigController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
