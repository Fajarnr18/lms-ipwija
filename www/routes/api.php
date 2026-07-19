<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AuditLogController;
use Illuminate\Support\Facades\Route;

// 8.2 Endpoint Autentikasi
Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Profil & Katalog (Tidak ada di spec spesifik 8, tapi kita keep)
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/catalog/{id_alat}', [CatalogController::class, 'show']);
    Route::get('/catalog/categories', [CatalogController::class, 'categories']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/users', [UserController::class, 'index'])->middleware('admin');

    // 8.3 Endpoint Manajemen Alat
    Route::get('/tools', [ToolController::class, 'index']);
    Route::get('/tools/{id_alat}', [ToolController::class, 'show']);
    
    Route::middleware('admin')->group(function () {
        Route::post('/tools', [ToolController::class, 'store']);
        Route::put('/tools/{id_alat}', [ToolController::class, 'update']);
        Route::delete('/tools/{id_alat}', [ToolController::class, 'destroy']);
    });

    // 8.4 Endpoint Peminjaman
    Route::get('/borrowings/overdue', [WebhookController::class, 'overdue']); // Bisa diakses internal
    Route::get('/borrowings/my', [BorrowingController::class, 'my'])->middleware('mahasiswa');
    
    Route::get('/borrowings', [BorrowingController::class, 'index'])->middleware('admin');
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::post('/borrowings', [BorrowingController::class, 'store'])->middleware('mahasiswa');
    
    Route::middleware('admin')->group(function () {
        Route::patch('/borrowings/{id}/approve', [BorrowingController::class, 'approve']);
        Route::patch('/borrowings/{id}/reject', [BorrowingController::class, 'reject']);
        Route::patch('/borrowings/{id}/return', [BorrowingController::class, 'kembali']);
    });

    // 8.5 Endpoint Inventaris Barang
    Route::middleware('admin')->group(function () {
        Route::get('/items', [ItemController::class, 'index']);
        Route::get('/items/{id}', [ItemController::class, 'show']);
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::post('/items/{id}/mutate', [ItemController::class, 'mutate']);
    });

    // 8.6 Endpoint Laporan & Audit
    Route::middleware('admin')->group(function () {
        Route::get('/reports/borrowings', [ReportController::class, 'borrowings']);
        Route::get('/reports/items', [ReportController::class, 'items']);
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
    });
});

