<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";

try {
    $routes = app('router')->getRoutes();
    foreach ($routes as $route) {
        echo $route->uri() . " -> " . ($route->getActionName() ?? 'closure') . "\n";
    }
} catch (Throwable $e) {
    echo "ERROR:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
}
