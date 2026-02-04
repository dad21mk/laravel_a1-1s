<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. ROUTE PUBLIC (Bisa diakses tanpa Token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- 2. ROUTE PRIVATE (Harus Login / Pakai Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout (Wajib di dalam sini agar $request->user() terbaca)
    Route::post('/logout', [AuthController::class, 'logout']); 
    
    // CRUD Produk & Kategori (Hanya bisa diakses member)
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);

    // Cek User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});