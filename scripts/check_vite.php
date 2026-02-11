<?php

// Test if manifest exists and is readable
$manifestPath = base_path('public/build/manifest.json');
echo "Manifest path: " . $manifestPath . "\n";
echo "File exists: " . (file_exists($manifestPath) ? "YES" : "NO") . "\n";

if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    echo "Manifest entries: " . count($manifest) . "\n";
    echo "CSS entry: " . ($manifest['resources/css/app.css']['file'] ?? 'NOT FOUND') . "\n";
    echo "JS entry: " . ($manifest['resources/js/app.js']['file'] ?? 'NOT FOUND') . "\n";
}

// Test @vite() helper output
echo "\n@vite output for CSS:\n";
$cssPath = Vite::asset('resources/css/app.css');
echo $cssPath . "\n";
