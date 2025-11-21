<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

echo "Autoload OK<br>";

$app = require __DIR__ . '/bootstrap/app.php';

echo "Bootstrap OK<br>";

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Kernel OK<br>";
