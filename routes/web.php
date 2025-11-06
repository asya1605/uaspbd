<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    SatuanController,
    VendorController,
    RoleController,
    UserController,
    BarangController,
    PengadaanController,
    PenjualanController,
    StokController
};

/*
|--------------------------------------------------------------------------
| 🏠 PUBLIC ACCESS
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'))->name('landing');

// 🔐 Authentication
Route::get('/login', [AuthController::class, 'form'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.do');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ⚙️ MASTER DATA MODULES
|--------------------------------------------------------------------------
*/

// === Role ===
Route::prefix('role')->name('role.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::post('{id}/update', [RoleController::class, 'update'])->name('update');
    Route::post('{id}/delete', [RoleController::class, 'delete'])->name('delete');
});

// === User ===
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::post('{id}/update', [UserController::class, 'update'])->name('update');
    Route::post('{id}/delete', [UserController::class, 'delete'])->name('delete');
});

// === Vendor ===
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::post('/', [VendorController::class, 'store'])->name('store');
    Route::post('{id}/update', [VendorController::class, 'update'])->name('update');
    Route::post('{id}/delete', [VendorController::class, 'delete'])->name('delete');
});

// === Satuan ===
Route::prefix('satuan')->name('satuan.')->group(function () {
    Route::get('/', [SatuanController::class, 'index'])->name('index');
    Route::post('/', [SatuanController::class, 'store'])->name('store');
    Route::post('{id}/update', [SatuanController::class, 'update'])->name('update');
    Route::post('{id}/delete', [SatuanController::class, 'delete'])->name('delete');
});

// === Barang ===
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::post('{id}/update', [BarangController::class, 'update'])->name('update');
    Route::post('{id}/delete', [BarangController::class, 'delete'])->name('delete');
});

/*
|--------------------------------------------------------------------------
| 📦 PENGADAAN MODULE
|--------------------------------------------------------------------------
*/
Route::prefix('pengadaan')->name('pengadaan.')->group(function () {
    Route::get('/', [PengadaanController::class, 'index'])->name('index');
    Route::get('/create', [PengadaanController::class, 'create'])->name('create');
    Route::post('/', [PengadaanController::class, 'store'])->name('store');
    Route::get('{id}/items', [PengadaanController::class, 'items'])->name('items');
    Route::post('{id}/items', [PengadaanController::class, 'addItem'])->name('addItem');
});

/*
|--------------------------------------------------------------------------
| 💰 PENJUALAN MODULE
|--------------------------------------------------------------------------
*/
Route::prefix('penjualan')->name('penjualan.')->group(function () {
    Route::get('/', [PenjualanController::class, 'index'])->name('index');
    Route::get('/create', [PenjualanController::class, 'create'])->name('create');
    Route::post('/', [PenjualanController::class, 'store'])->name('store');
    Route::get('{id}/items', [PenjualanController::class, 'items'])->name('items');
    Route::post('{id}/items', [PenjualanController::class, 'addItem'])->name('addItem');
});

/*
|--------------------------------------------------------------------------
| 🧾 STOK MODULE
|--------------------------------------------------------------------------
|
| Mengatur form update stok dan proses insert stok masuk/keluar
| Controller: StokController
|
*/
Route::prefix('stok')->name('stok.')->group(function () {
    Route::get('/update', [StokController::class, 'index'])->name('update');
    Route::post('/update', [StokController::class, 'update'])->name('update.post');
});

/*
|--------------------------------------------------------------------------
| 🚨 FALLBACK (404 PAGE)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
