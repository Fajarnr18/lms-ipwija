<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/tools', [ToolController::class, 'index']);
    Route::get('/tools/{id_alat}', [ToolController::class, 'show']);
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/catalog/{id_alat}', [CatalogController::class, 'show']);
    Route::get('/catalog/categories', [CatalogController::class, 'categories']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1/admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin']);

    Route::post('/tools', [ToolController::class, 'store']);
    Route::put('/tools/{id_alat}', [ToolController::class, 'update']);
    Route::delete('/tools/{id_alat}', [ToolController::class, 'destroy']);
    Route::patch('/tools/{id_alat}/restore', [ToolController::class, 'restore']);

    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::post('/borrowings/{id}/approve', [BorrowingController::class, 'approve']);
    Route::post('/borrowings/{id}/reject', [BorrowingController::class, 'reject']);
    Route::post('/borrowings/{id}/proses', [BorrowingController::class, 'proses']);
    Route::post('/borrowings/{id}/kembali', [BorrowingController::class, 'kembali']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
});

Route::middleware(['auth:sanctum', 'mahasiswa'])->prefix('v1/mahasiswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/tambah/{id_alat}', [CartController::class, 'tambah']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::get('/cart/hapus/{id}', [CartController::class, 'hapus']);
    Route::post('/cart/ajukan', [CartController::class, 'ajukan']);
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::get('/borrowings/history', [BorrowingController::class, 'riwayat']);
});

Route::middleware(['auth:sanctum', 'dosen'])->prefix('v1/dosen')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dosen']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/tambah/{id_alat}', [CartController::class, 'tambah']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::get('/cart/hapus/{id}', [CartController::class, 'hapus']);
    Route::post('/cart/ajukan', [CartController::class, 'ajukan']);
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::get('/borrowings/history', [BorrowingController::class, 'riwayat']);
});
