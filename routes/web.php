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
    KartuStokController,
    MarginPenjualanController
};

/*
|--------------------------------------------------------------------------
| 🏠 PUBLIC ACCESS
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('landing'))->name('landing');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'form')->name('login.form');
    Route::post('/login', 'login')->name('login.do');
    Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware('auth.required');
    Route::get('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| ⚙️ MASTER DATA (ADMIN READ ONLY • SUPER_ADMIN FULL CRUD)
|--------------------------------------------------------------------------
*/

// === Role === (super_admin only)
Route::prefix('role')->name('role.')
    ->middleware(['auth.required','role:super_admin'])
    ->controller(RoleController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('{id}/update', 'update')->name('update');
        Route::post('{id}/delete', 'delete')->name('delete');
        Route::post('/check', 'check')->name('check'); 
    });

// === User ===
Route::prefix('users')->name('users.')
    ->middleware('auth.required')
    ->controller(UserController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index'); // admin bisa read
        
        Route::post('/', 'store')->middleware('role:super_admin')->name('store');
        Route::post('{id}/update', 'update')->middleware('role:super_admin')->name('update');
        Route::post('{id}/delete', 'delete')->middleware('role:super_admin')->name('delete');
        Route::post('/check', 'check')->middleware('role:super_admin')->name('check');
    });

// === Vendor ===
Route::prefix('vendor')->name('vendor.')
    ->middleware('auth.required')
    ->controller(VendorController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->middleware('role:super_admin')->name('store');
        Route::post('{id}/update', 'update')->middleware('role:super_admin')->name('update');
        Route::post('{id}/delete', 'delete')->middleware('role:super_admin')->name('delete');
        Route::post('/check', 'check')->middleware('role:super_admin')->name('check');
    });

// === Satuan ===
Route::prefix('satuan')->name('satuan.')
    ->middleware('auth.required')
    ->controller(SatuanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->middleware('role:super_admin')->name('store');
        Route::post('{id}/update', 'update')->middleware('role:super_admin')->name('update');
        Route::post('{id}/delete', 'delete')->middleware('role:super_admin')->name('delete');
        Route::post('/check', 'check')->middleware('role:super_admin')->name('check');
    });

// === Barang ===
Route::prefix('barang')->name('barang.')
    ->middleware('auth.required')
    ->controller(BarangController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->middleware('role:super_admin')->name('store');
        Route::post('{id}/update', 'update')->middleware('role:super_admin')->name('update');
        Route::post('{id}/delete', 'delete')->middleware('role:super_admin')->name('delete');
        Route::post('/check', 'check')->middleware('role:super_admin')->name('check');
    });

/*
|--------------------------------------------------------------------------
| 💼 TRANSAKSI MODULES (ADMIN READ ONLY • SUPER_ADMIN FULL CRUD)
|--------------------------------------------------------------------------
*/

// === PENGADAAN ===
Route::prefix('pengadaan')->name('pengadaan.')
    ->middleware('auth.required')
    ->controller(PengadaanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/{id}/items', 'items')->name('items'); 

        Route::get('/create', 'create')->middleware('role:super_admin')->name('create');
        Route::post('/store', 'store')->middleware('role:super_admin')->name('store');

        Route::post('/{id}/add-item', 'addItem')->middleware('role:super_admin')->name('addItem');
        Route::post('/{id}/delete', 'delete')->middleware('role:super_admin')->name('delete');
        Route::post('/{id}/update-status', 'updateStatus')->middleware('role:super_admin')->name('updateStatus');
        Route::get('/{id}/status', 'toggleStatus')->middleware('role:super_admin')->name('toggleStatus');
    });

// === PENERIMAAN ===
Route::prefix('penerimaan')->name('penerimaan.')
    ->middleware('auth.required')
    ->controller(PenerimaanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/load-barang/{id}', 'loadBarang')->name('load');
        Route::get('/{id}/items', 'items')->name('items');

        Route::get('/create', 'create')->middleware('role:super_admin')->name('create');
        Route::post('/store', 'store')->middleware('role:super_admin')->name('store');
        Route::post('/{id}/add-item', 'addItem')->middleware('role:super_admin')->name('addItem');
        Route::post('/{id}/confirm', 'confirm')->middleware('role:super_admin')->name('confirm');
    });

/*
|--------------------------------------------------------------------------
| 🛒 PENJUALAN (ADMIN & SUPER_ADMIN FULL CRUD)
|--------------------------------------------------------------------------
*/

Route::prefix('penjualan')->name('penjualan.')
    ->middleware('auth.required')
    ->controller(PenjualanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('{id}/items', 'items')->name('items');  // READ-only

        // Admin & SuperAdmin boleh CUD
        Route::get('create', 'create')->middleware('role:admin')->name('create');
        Route::post('store', 'store')->middleware('role:admin')->name('store');
        Route::post('{id}/add-item', 'addItem')->middleware('role:admin')->name('addItem');
        Route::post('{id}/delete', 'delete')->middleware('role:admin')->name('delete');
    });

/*
|--------------------------------------------------------------------------
| 📦 KARTU STOK (READ ONLY)
|--------------------------------------------------------------------------
*/

Route::prefix('kartu-stok')->name('kartustok.')
    ->middleware('auth.required')
    ->controller(KartuStokController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('{idbarang}/history', 'history')->name('history');
    });

/*
|--------------------------------------------------------------------------
| 💰 MARGIN PENJUALAN (SUPER_ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Route::prefix('margin-penjualan')->name('margin.')
    ->middleware(['auth.required','role:super_admin'])
    ->controller(MarginPenjualanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('{id}/update', 'update')->name('update');
        Route::post('{id}/delete', 'delete')->name('delete');
    });

/*
|--------------------------------------------------------------------------
| 🔧 UTILITAS UPDATE STOK MANUAL (SUPER_ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Route::get('/update-stok', [KartuStokController::class, 'index'])
    ->middleware(['auth.required','role:super_admin'])
    ->name('stok.update');

Route::post('/update-stok', [KartuStokController::class, 'store'])
    ->middleware(['auth.required','role:super_admin'])
    ->name('stok.update.post');

/*
|--------------------------------------------------------------------------
| 🚨 FALLBACK (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
