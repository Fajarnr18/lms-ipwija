<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Mahasiswa\BorrowingController as MhsBorrowingController;
use App\Http\Controllers\Mahasiswa\CartController;
use App\Http\Controllers\Mahasiswa\CatalogController;
use App\Http\Controllers\Mahasiswa\DashboardController as MhsDashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('mhs.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Legacy tool routes
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
    Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
    Route::get('/tools/{id_alat}/edit', [ToolController::class, 'edit'])->name('tools.edit');
    Route::put('/tools/{id_alat}', [ToolController::class, 'update'])->name('tools.update');
    Route::delete('/tools/{id_alat}', [ToolController::class, 'destroy'])->name('tools.destroy');

    // --- Admin Routes ---
    Route::prefix('admin')->name('admin.')->middleware('can:admin-access')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/tools', [AdminToolController::class, 'index'])->name('tools.index');
        Route::get('/tools/create', [AdminToolController::class, 'create'])->name('tools.create');
        Route::post('/tools', [AdminToolController::class, 'store'])->name('tools.store');
        Route::get('/tools/{id_alat}/edit', [AdminToolController::class, 'edit'])->name('tools.edit');
        Route::put('/tools/{id_alat}', [AdminToolController::class, 'update'])->name('tools.update');
        Route::delete('/tools/{id_alat}', [AdminToolController::class, 'destroy'])->name('tools.destroy');

        Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/{id}', [AdminBorrowingController::class, 'show'])->name('borrowings.show');
        Route::post('/borrowings/{borowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::post('/borrowings/{borowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::get('/borrowings/{borowing}/return', [AdminBorrowingController::class, 'returnForm'])->name('borrowings.return');
        Route::post('/borrowings/{borowing}/return', [AdminBorrowingController::class, 'returnSubmit'])->name('borrowings.return-submit');

        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::get('/items/{id}/mutation', [ItemController::class, 'mutation'])->name('items.mutation');
        Route::post('/items/{id}/mutation', [ItemController::class, 'mutationStore'])->name('items.mutation-store');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    });

    // --- Mahasiswa Routes ---
    Route::prefix('mhs')->name('mhs.')->middleware('can:mahasiswa-access')->group(function () {
        Route::get('/dashboard', [MhsDashboardController::class, 'index'])->name('dashboard');

        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add/{id_alat}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/submit', [CartController::class, 'submit'])->name('cart.submit');

        Route::get('/borrowings', [MhsBorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/{id}', [MhsBorrowingController::class, 'show'])->name('borrowings.show');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});
