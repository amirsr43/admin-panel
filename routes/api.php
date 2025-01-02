<?php

use App\Http\Controllers\api\AssociationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\PortfolioController;
use App\Http\Controllers\api\ServiceController;

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

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'userInfo'])->middleware('auth:sanctum');

// Protected Product Routes
Route::middleware('auth:sanctum')->group(function () {

    // products
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/categories/{categoryId}/products', [ProductController::class, 'productsByCategory']);

    // services
    Route::get('/services', [ServiceController::class, 'index']); // Menampilkan semua services
    Route::post('/services', [ServiceController::class, 'store']); // Menambahkan service
    Route::get('/services/{id}', [ServiceController::class, 'show']); // Menampilkan service berdasarkan ID
    Route::put('/services/{id}', [ServiceController::class, 'update']); // Mengupdate service
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']); // Menghapus service

    // customers
    Route::get('/customers', [CustomerController::class, 'index']); // Menampilkan semua customers
    Route::post('/customers', [CustomerController::class, 'store']); // Menambahkan customer
    Route::get('/customers/{id}', [CustomerController::class, 'show']); // Menampilkan customer berdasarkan ID
    Route::put('/customers/{id}', [CustomerController::class, 'update']); // Mengupdate customer
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']); // Menghapus customer

    // portfolio
    Route::get('/portfolios', [PortfolioController::class, 'index']); // Menampilkan semua portfolio
    Route::post('/portfolios', [PortfolioController::class, 'store']); // Menambahkan portfolio baru
    Route::get('/portfolios/{id}', [PortfolioController::class, 'show']); // Menampilkan portfolio berdasarkan ID
    Route::put('/portfolios/{id}', [PortfolioController::class, 'update']); // Mengupdate portfolio
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy']); // Menghapus portfolio

    // associations
    Route::get('/associations', [AssociationController::class, 'index']); // Menampilkan semua association
    Route::post('/associations', [AssociationController::class, 'store']); // Menambahkan association baru
    Route::get('/associations/{id}', [AssociationController::class, 'show']); // Menampilkan association berdasarkan ID
    Route::put('/associations/{id}', [AssociationController::class, 'update']); // Mengupdate association
    Route::delete('/associations/{id}', [AssociationController::class, 'destroy']); // Menghapus association
});
