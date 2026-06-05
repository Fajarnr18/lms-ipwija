<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ToolController;
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
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {
    Route::post('/tools', [ToolController::class, 'store']);
    Route::put('/tools/{id_alat}', [ToolController::class, 'update']);
    Route::delete('/tools/{id_alat}', [ToolController::class, 'destroy']);
    Route::patch('/tools/{id_alat}/restore', [ToolController::class, 'restore']);

    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Admin dashboard']);
    });
});

Route::middleware(['auth:sanctum', 'mahasiswa'])->prefix('v1/mahasiswa')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Mahasiswa dashboard']);
    });
});
