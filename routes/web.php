<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    SatuanController,
    VendorController,
    RoleController,
    UserController,
    PenjualanController,
    BarangController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Versi tanpa middleware custom (RequireAuth / RequireRole)
| Supaya semua fitur tetap bisa diakses sementara.
| Nanti bisa diaktifkan kembali setelah sistem login stabil.
*/

# =========================
# 🔓 PUBLIC (No Auth)
# =========================
Route::get('/', fn () => view('landing'));

Route::get('/login', [AuthController::class, 'form'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.do');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

# =========================
# 🔐 PRIVATE (sementara tanpa middleware)
# =========================

# Dashboard
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

# =========================
# 👑 SUPER ADMIN ACCESS
# =========================
Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
Route::post('/satuan/{id}/update', [SatuanController::class, 'update'])->name('satuan.update');
Route::post('/satuan/{id}/delete', [SatuanController::class, 'delete'])->name('satuan.delete');

Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
Route::post('/vendor/{id}/update', [VendorController::class, 'update'])->name('vendor.update');
Route::post('/vendor/{id}/delete', [VendorController::class, 'delete'])->name('vendor.delete');

Route::get('/role', [RoleController::class, 'index'])->name('role.index');
Route::post('/role', [RoleController::class, 'store'])->name('role.store');
Route::post('/role/{id}/update', [RoleController::class, 'update'])->name('role.update');
Route::post('/role/{id}/delete', [RoleController::class, 'delete'])->name('role.delete');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
Route::post('/users/{id}/delete', [UserController::class, 'delete'])->name('users.delete');

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::post('/barang/{id}/update', [BarangController::class, 'update'])->name('barang.update');
Route::post('/barang/{id}/delete', [BarangController::class, 'delete'])->name('barang.delete');

# =========================
# 🧾 ADMIN ACCESS
# =========================
Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::post('/penjualan/{id}/items', [PenjualanController::class, 'addItem'])->name('penjualan.addItem');

# =========================
# ⚠️ FALLBACK (404)
# =========================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
