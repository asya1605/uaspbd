<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\MarginPenjualanController;

$controllers = [
    AuthController::class,
    RoleController::class,
    UserController::class,
    VendorController::class,
    SatuanController::class,
    BarangController::class,
    PengadaanController::class,
    PenerimaanController::class,
    PenjualanController::class,
    KartuStokController::class,
    MarginPenjualanController::class,
];

echo "<pre>";

foreach ($controllers as $c) {
    try {
        app($c);
        echo "OK - $c\n";
    } catch (Throwable $e) {
        echo "ERROR in $c:\n";
        echo $e->getMessage() . "\n";
        echo $e->getFile() . ":" . $e->getLine() . "\n";
        break;
    }
}
