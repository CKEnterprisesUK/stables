<?php

// Simple setup script to create the storage symlink
// DELETE THIS FILE AFTER USE

// Token check
if (!isset($_GET['token']) || $_GET['token'] !== 'setup-stables-2024') {
    http_response_code(403);
    die('Forbidden: Invalid or missing token.');
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>\n";
echo "=== Storage Symlink Setup ===\n\n";

// Run storage:link
echo "Running storage:link...\n";
Illuminate\Support\Facades\Artisan::call('storage:link');
echo Illuminate\Support\Facades\Artisan::output();

// Confirm the symlink
echo "\n--- Verification ---\n";
$storagePath = public_path('storage');
if (file_exists($storagePath)) {
    echo "public/storage exists: YES\n";
    echo "Realpath: " . realpath($storagePath) . "\n";
    echo "Is symlink: " . (is_link($storagePath) ? 'YES' : 'NO') . "\n";
} else {
    echo "public/storage exists: NO (something went wrong)\n";
}

echo "\n⚠️  REMINDER: Delete this file (public/setup.php) now!\n";
echo "</pre>\n";
