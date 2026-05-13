<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$base = config('api.base_url');
echo "Base URL: " . $base . "\n\n";

// Test news
$r = \Illuminate\Support\Facades\Http::timeout(5)->get($base . '/news');
echo "=== /news ===\n";
echo "Status: " . $r->status() . "\n";
echo substr($r->body(), 0, 2000) . "\n\n";

// Test blog
$r2 = \Illuminate\Support\Facades\Http::timeout(5)->get($base . '/blog');
echo "=== /blog ===\n";
echo "Status: " . $r2->status() . "\n";
echo substr($r2->body(), 0, 2000) . "\n";
