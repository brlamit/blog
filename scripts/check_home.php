<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Log in as user id 1
Auth::loginUsingId(1);

$request = Request::create('/dashboard', 'GET', []);
$controller = new App\Http\Controllers\PostController();
$response = $controller->home($request);

if ($response instanceof Illuminate\Contracts\View\View) {
    $data = $response->getData();
    echo "View: " . $response->name() . PHP_EOL;
    echo "Keys: " . implode(', ', array_keys($data)) . PHP_EOL;
    if (isset($data['myPosts'])) {
        echo "myPosts count: " . count($data['myPosts']) . PHP_EOL;
    }
    if (isset($data['allPosts'])) {
        // if paginator
        if (method_exists($data['allPosts'], 'total')) {
            echo "allPosts total: " . $data['allPosts']->total() . PHP_EOL;
            echo "allPosts perPage: " . $data['allPosts']->perPage() . PHP_EOL;
        } else {
            echo "allPosts count: " . count($data['allPosts']) . PHP_EOL;
        }
    }
} else {
    echo "Controller did not return a View; class: " . get_class($response) . PHP_EOL;
}
