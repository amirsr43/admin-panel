<?php

use App\Http\Controllers\api\AssociationController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\PortfolioController;
use App\Http\Controllers\api\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
| Register your API routes here. All routes are loaded by the RouteServiceProvider
| and assigned to the "api" middleware group. Make something great!
*/

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'userInfo'])->middleware('auth:sanctum');

// Public Routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'productsByCategory']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/category/{kategoriId}', [CustomerController::class, 'showByCategory']);
Route::get('/portfolios', [PortfolioController::class, 'index']);
Route::get('/portfolios/group/{groupId}', [PortfolioController::class, 'portfoliosByGroup']);
Route::get('/associations', [AssociationController::class, 'index']);

// Protected Routes requiring Authentication
Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function () {

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::get('{id}', [ProductController::class, 'show']);
        Route::put('{id}', [ProductController::class, 'update']);
        Route::delete('{id}', [ProductController::class, 'destroy']);
    });

    // Service Routes
    Route::prefix('services')->group(function () {
        Route::post('/', [ServiceController::class, 'store']);
        Route::get('{id}', [ServiceController::class, 'show']);
        Route::put('{id}', [ServiceController::class, 'update']);
        Route::delete('{id}', [ServiceController::class, 'destroy']);
    });

    // Customer Routes
    Route::prefix('customers')->group(function () {
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('{id}', [CustomerController::class, 'show']);
        Route::put('{id}', [CustomerController::class, 'update']);
        Route::delete('{id}', [CustomerController::class, 'destroy']);
    });

    // Portfolio Routes
    Route::prefix('portfolios')->group(function () {
        Route::post('/', [PortfolioController::class, 'store']);
        Route::get('{id}', [PortfolioController::class, 'show']);
        Route::put('{id}', [PortfolioController::class, 'update']);
        Route::delete('{id}', [PortfolioController::class, 'destroy']);
    });

    // Association Routes
    Route::prefix('associations')->group(function () {
        Route::post('/', [AssociationController::class, 'store']);
        Route::get('{id}', [AssociationController::class, 'show']);
        Route::put('{id}', [AssociationController::class, 'update']);
        Route::delete('{id}', [AssociationController::class, 'destroy']);
    });
});
