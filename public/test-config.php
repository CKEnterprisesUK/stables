<?php
/**
 * ⚠️  WARNING: DELETE THIS FILE AFTER TESTING ⚠️
 * 
 * This file exposes database connection details and should NEVER
 * be left on a production or publicly accessible server.
 */

echo "<h1 style='color:red;border:3px solid red;padding:10px;text-align:center;'>⚠️ DELETE THIS FILE AFTER TESTING ⚠️</h1>";

// Parse .env file
$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    die("<p style='color:red;'><strong>Error:</strong> .env file not found at: $envPath</p>");
}

$env = [];
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    // Skip comments
    if (str_starts_with(trim($line), '#')) {
        continue;
    }

    if (strpos($line, '=') !== false) {
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Remove surrounding quotes
        $value = trim($value, '"\'');
        $env[$key] = $value;
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? '';
$username = $env['DB_USERNAME'] ?? '';
$password = $env['DB_PASSWORD'] ?? '';

// Mask password for display
$maskedPassword = strlen($password) > 2
    ? substr($password, 0, 1) . str_repeat('*', strlen($password) - 2) . substr($password, -1)
    : '***';

echo "<h2>Connection Details</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Port:</strong> $port</li>";
echo "<li><strong>Database:</strong> $database</li>";
echo "<li><strong>Username:</strong> $username</li>";
echo "<li><strong>Password:</strong> $maskedPassword</li>";
echo "</ul>";

// Attempt connection
echo "<h2>Connection Test</h2>";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);

    $version = $pdo->query('SELECT VERSION()')->fetchColumn();

    echo "<p style='color:green;font-size:1.2em;'><strong>✅ Connection successful!</strong></p>";
    echo "<p><strong>MySQL Version:</strong> $version</p>";

    $pdo = null;
} catch (PDOException $e) {
    echo "<p style='color:red;font-size:1.2em;'><strong>❌ Connection failed!</strong></p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p style='color:red;'><em>Remember to delete this file when done testing.</em></p>";
