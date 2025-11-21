<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\View;

echo "<pre>";

$views = [
    'landing',
    'dashboard',
    'layouts.master',
    'layouts.app',
];

foreach ($views as $v) {
    try {
        echo "Testing view: $v\n";
        echo View::make($v)->render();
        echo "OK: $v\n\n";
    } catch (Throwable $e) {
        echo "❌ ERROR in $v:\n";
        echo $e->getMessage() . "\n";
        echo $e->getFile() . ":" . $e->getLine() . "\n";
        break;
    }
}
