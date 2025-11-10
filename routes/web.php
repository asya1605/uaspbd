<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    RoleController,
    UserController,
    VendorController,
    SatuanController,
    BarangController,
    PengadaanController,
    PenerimaanController,
    PenjualanController,
    ReturrController,
    KartuStokController,
    MarginPenjualanController
};

/*
|--------------------------------------------------------------------------
| 🏠 PUBLIC ACCESS
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'))->name('landing');

// 🔐 Authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'form')->name('login.form');
    Route::post('/login', 'login')->name('login.do');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| ⚙️ MASTER DATA MODULES
|--------------------------------------------------------------------------
| Semua modul utama yang sifatnya data master (tidak otomatisasi stok)
|--------------------------------------------------------------------------
*/

// === Role ===
Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/update', 'update')->name('update');
    Route::post('{id}/delete', 'delete')->name('delete');
    Route::post('/check', 'check')->name('check'); // 🔍 AJAX cek duplikat
});


// === User ===
Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/update', 'update')->name('update');
    Route::post('{id}/delete', 'delete')->name('delete');
    Route::post('/check', 'check')->name('check'); // 🔍 optional AJAX duplikat
});


// === Vendor ===
Route::prefix('vendor')->name('vendor.')->controller(VendorController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/update', 'update')->name('update');
    Route::post('{id}/delete', 'delete')->name('delete');
    Route::post('/check', 'check')->name('check'); // ✅ tambahan untuk AJAX duplikat
});


// === Satuan ===
Route::prefix('satuan')->name('satuan.')->controller(SatuanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/update', 'update')->name('update');
    Route::post('{id}/delete', 'delete')->name('delete');
    Route::post('/check', 'check')->name('check'); // ✅ tambahan untuk AJAX duplikat (opsional)
});


// === Barang ===
Route::prefix('barang')->name('barang.')->controller(BarangController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/update', 'update')->name('update');
    Route::post('{id}/delete', 'delete')->name('delete');
    Route::post('/check', 'check')->name('check'); // ✅ FIXED: jadi /barang/check
});

/*
|--------------------------------------------------------------------------
| 💼 TRANSAKSI MODULES
|--------------------------------------------------------------------------
| Semua aktivitas yang memengaruhi stok & keuangan.
|--------------------------------------------------------------------------
*/

// 📦 PENGADAAN
Route::prefix('pengadaan')->name('pengadaan.')->controller(PengadaanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{id}/items', 'items')->name('items'); // <— ini penting!
    Route::post('/{id}/add-item', 'addItem')->name('addItem');
    Route::post('/{id}/delete', 'delete')->name('delete');
});


//  PENERIMAAN
Route::prefix('penerimaan')->name('penerimaan.')->controller(PenerimaanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('{id}/items', 'items')->name('items');
    Route::post('{id}/items', 'addItem')->name('addItem');
    Route::post('{id}/confirm', 'confirm')->name('confirm'); //
});

//  PENJUALAN
Route::prefix('penjualan')->name('penjualan.')->controller(PenjualanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('{id}/items', 'items')->name('items');
    Route::post('{id}/items', 'addItem')->name('addItem');
});

//  KARTU STOK
Route::prefix('kartu-stok')->name('kartu.')->controller(KartuStokController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('{id}/delete', 'delete')->name('delete');
});

// MARGIN PENJUALAN
Route::prefix('margin-penjualan')->name('margin.')->group(function () {
    Route::get('/', [MarginPenjualanController::class, 'index'])->name('index');
    Route::post('/', [MarginPenjualanController::class, 'store'])->name('store');
    Route::post('{id}/update', [MarginPenjualanController::class, 'update'])->name('update');
    Route::post('{id}/delete', [MarginPenjualanController::class, 'delete'])->name('delete');
});





/*
|--------------------------------------------------------------------------
| 🔧 UTILITAS STOK MANUAL
|--------------------------------------------------------------------------
*/
Route::get('/update-stok', [KartuStokController::class, 'index'])->name('stok.update');
Route::post('/update-stok', [KartuStokController::class, 'store'])->name('stok.update.post');

/*
|--------------------------------------------------------------------------
| 🚨 FALLBACK (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
