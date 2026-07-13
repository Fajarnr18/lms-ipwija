<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dosen\BorrowingController as DosenBorrowingController;
use App\Http\Controllers\Dosen\CartController as DosenCartController;
use App\Http\Controllers\Dosen\CatalogController as DosenCatalogController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Mahasiswa\BorrowingController as MhsBorrowingController;
use App\Http\Controllers\Mahasiswa\CartController;
use App\Http\Controllers\Mahasiswa\CatalogController;
use App\Http\Controllers\Mahasiswa\DashboardController as MhsDashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/_import-tools', function () {
    Artisan::call('import:tools-to-items');
    return redirect()->route('admin.inventaris.index')->with('success', 'Import data tools ke items berhasil!');
})->name('tools.import');

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }
        return redirect()->route('mhs.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/debug-session', function() {
    return [
        'id' => auth()->id(),
        'user' => auth()->user(),
        'role' => auth()->user()?->role,
        'is_active' => auth()->user()?->is_active,
        'mahasiswa_gate' => \Illuminate\Support\Facades\Gate::allows('mahasiswa-access'),
        'admin_gate' => \Illuminate\Support\Facades\Gate::allows('admin-access'),
        'dosen_gate' => \Illuminate\Support\Facades\Gate::allows('dosen-access'),
    ];
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->middleware('can:admin-access')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/alat', [AdminToolController::class, 'index'])->name('alat.index');
        Route::get('/alat/tambah', [AdminToolController::class, 'create'])->name('alat.create');
        Route::post('/alat', [AdminToolController::class, 'store'])->name('alat.store');
        Route::get('/alat/{id_alat}', [AdminToolController::class, 'show'])->name('alat.show');
        Route::get('/alat/{id_alat}/edit', [AdminToolController::class, 'edit'])->name('alat.edit');
        Route::put('/alat/{id_alat}', [AdminToolController::class, 'update'])->name('alat.update');
        Route::delete('/alat/{id_alat}', [AdminToolController::class, 'destroy'])->name('alat.destroy');

        Route::get('/peminjaman', [AdminBorrowingController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/aktif', [AdminBorrowingController::class, 'aktif'])->name('peminjaman.aktif');
        Route::get('/peminjaman/{borowing}/kembali', [AdminBorrowingController::class, 'formKembali'])->name('peminjaman.kembali-form');
        Route::get('/peminjaman/{borowing}', [AdminBorrowingController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{borowing}/catatan', [AdminBorrowingController::class, 'updateCatatan'])->name('peminjaman.catatan');
        Route::post('/peminjaman/{borowing}/approve', [AdminBorrowingController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{borowing}/reject', [AdminBorrowingController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman/{borowing}/proses', [AdminBorrowingController::class, 'prosesPeminjaman'])->name('peminjaman.proses');
        Route::post('/peminjaman/{borowing}/kembali', [AdminBorrowingController::class, 'kembali'])->name('peminjaman.kembali');
        Route::get('/peminjaman/export/csv', [AdminBorrowingController::class, 'exportCsv'])->name('peminjaman.export-csv');

        Route::get('/inventaris', [ItemController::class, 'index'])->name('inventaris.index');
        Route::get('/inventaris/tambah', [ItemController::class, 'create'])->name('inventaris.create');
        Route::post('/inventaris', [ItemController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/{id}/edit', [ItemController::class, 'edit'])->name('inventaris.edit');
        Route::get('/inventaris/{id}/detaildatabarang', [ItemController::class, 'detail'])->name('inventaris.detail');
        Route::put('/inventaris/{id}', [ItemController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{id}', [ItemController::class, 'destroy'])->name('inventaris.destroy');
        Route::get('/inventaris/{id}/mutasi', [ItemController::class, 'mutasi'])->name('inventaris.mutasi');
        Route::post('/inventaris/{id}/mutasi', [ItemController::class, 'mutasiStore'])->name('inventaris.mutasi-store');

        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [ReportController::class, 'export'])->name('laporan.export');

        Route::get('/audit-trail', [AuditController::class, 'index'])->name('audit-trail.index');
        Route::get('/audit-trail/export', [AuditController::class, 'export'])->name('audit-trail.export');
        Route::get('/audit-trail/{id_log}', [AuditController::class, 'show'])->name('audit-trail.show');

        Route::any('/audit-trail/{any}', function () {
            abort(403, 'Akses ke data audit trail tidak diizinkan.');
        })->where('any', '.*');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
    });

    Route::middleware('can:mahasiswa-access')->group(function () {
        Route::get('/dashboard', [MhsDashboardController::class, 'index'])->name('mhs.dashboard');

        Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index');
        Route::get('/katalog/{id_alat}', [CatalogController::class, 'show'])->name('katalog.show');

        Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
        Route::post('/keranjang/tambah/{id_alat}', [CartController::class, 'tambah'])->name('keranjang.tambah');
        Route::get('/keranjang/hapus/{id}', [CartController::class, 'hapus'])->name('keranjang.hapus');
        Route::post('/keranjang/ajukan', [CartController::class, 'ajukan'])->name('keranjang.ajukan');

        Route::get('/peminjaman', [MhsBorrowingController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/riwayat', [MhsBorrowingController::class, 'riwayat'])->name('peminjaman.riwayat');
        Route::get('/peminjaman/{id}', [MhsBorrowingController::class, 'show'])->name('peminjaman.detail');
        Route::get('/peminjaman/{id}/pdf', [MhsBorrowingController::class, 'exportPdf'])->name('peminjaman.pdf');

        Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
        Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
    });

    Route::middleware('can:dosen-access')->group(function () {
        Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');

        Route::get('/dosen/katalog', [DosenCatalogController::class, 'index'])->name('dosen.katalog.index');
        Route::get('/dosen/katalog/{id_alat}', [DosenCatalogController::class, 'show'])->name('dosen.katalog.show');

        Route::get('/dosen/keranjang', [DosenCartController::class, 'index'])->name('dosen.keranjang.index');
        Route::post('/dosen/keranjang/tambah/{id_alat}', [DosenCartController::class, 'tambah'])->name('dosen.keranjang.tambah');
        Route::get('/dosen/keranjang/hapus/{id}', [DosenCartController::class, 'hapus'])->name('dosen.keranjang.hapus');
        Route::post('/dosen/keranjang/ajukan', [DosenCartController::class, 'ajukan'])->name('dosen.keranjang.ajukan');

        Route::get('/dosen/peminjaman', [DosenBorrowingController::class, 'index'])->name('dosen.peminjaman.index');
        Route::get('/dosen/peminjaman/riwayat', [DosenBorrowingController::class, 'riwayat'])->name('dosen.peminjaman.riwayat');
        Route::get('/dosen/peminjaman/{id}', [DosenBorrowingController::class, 'show'])->name('dosen.peminjaman.detail');
        Route::get('/dosen/peminjaman/{id}/pdf', [DosenBorrowingController::class, 'exportPdf'])->name('dosen.peminjaman.pdf');

        Route::get('/dosen/profil', [DosenProfileController::class, 'index'])->name('dosen.profil.index');
        Route::post('/dosen/profil/update', [DosenProfileController::class, 'update'])->name('dosen.profil.update');
    });
});
