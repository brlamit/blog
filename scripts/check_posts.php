<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;

$posts = Post::with('user')->limit(10)->get()->map(function($p){
    return [
        'id' => $p->id,
        'slug' => $p->slug,
        'user_id' => $p->user_id,
        'title' => $p->title,
        'published_at' => $p->published_at ? (string)$p->published_at : null,
    ];
});

echo json_encode($posts->toArray(), JSON_PRETTY_PRINT);
