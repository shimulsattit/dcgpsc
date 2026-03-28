<?php
/**
 * Update .env file for local development
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die("Error: .env file not found!\n");
}

$envContent = file_get_contents($envFile);

// Update database configuration for local development
$replacements = [
    '/DB_DATABASE=.*/' => 'DB_DATABASE=hajee_local',
    '/DB_USERNAME=.*/' => 'DB_USERNAME=root',
    '/DB_PASSWORD=.*/' => 'DB_PASSWORD=',
    '/APP_URL=.*/' => 'APP_URL=http://hajee.test',
];

foreach ($replacements as $pattern => $replacement) {
    $envContent = preg_replace($pattern, $replacement, $envContent);
}

file_put_contents($envFile, $envContent);

echo "✓ .env file updated successfully for local development!\n";
echo "Database: hajee_local\n";
echo "Username: root\n";
echo "Password: (empty)\n";
echo "APP_URL: http://hajee.test\n";
