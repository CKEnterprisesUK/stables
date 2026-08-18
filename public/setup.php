<?php

/**
 * Browser-based setup script for shared hosting (no SSH access).
 * Runs Laravel migrations and database seeding.
 *
 * Usage: Visit setup.php?token=setup-stables-2024
 *
 * !! DELETE THIS FILE IMMEDIATELY AFTER SETUP !!
 */

// Token check
$expectedToken = 'setup-stables-2024';

if (!isset($_GET['token']) || $_GET['token'] !== $expectedToken) {
    http_response_code(403);
    echo '<h1>Access Denied</h1>';
    echo '<p>Invalid or missing token.</p>';
    exit;
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .warning { background: #ff4444; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .output { background: #1a1a1a; color: #0f0; padding: 20px; border-radius: 8px; font-family: monospace; white-space: pre-wrap; margin-bottom: 20px; }
        .success { background: #28a745; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="warning">
        <h1>&#9888; WARNING</h1>
        <p><strong>DELETE THIS FILE IMMEDIATELY AFTER SETUP!</strong></p>
        <p>Leaving this file on the server is a security risk.</p>
    </div>

    <h2>Running Migrations...</h2>
    <div class="output"><?php
        Artisan::call('migrate', ['--force' => true]);
        echo htmlspecialchars(Artisan::output());
    ?></div>

    <h2>Running Database Seeding...</h2>
    <div class="output"><?php
        Artisan::call('db:seed', ['--force' => true]);
        echo htmlspecialchars(Artisan::output());
    ?></div>

    <div class="success">
        <h2>&#10004; Setup Complete!</h2>
        <p>Migrations and seeding have been executed successfully.</p>
    </div>

    <div class="warning">
        <h1>&#9888; NOW DELETE THIS FILE</h1>
        <p>Remove <code>public/setup.php</code> from your server immediately.</p>
    </div>
</body>
</html>
