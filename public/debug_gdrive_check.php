<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Storage;

try {
    echo "Testing Google Drive connection...\n";
    $files = Storage::disk('gdrive')->allFiles();
    echo "Connection Successful! Found " . count($files) . " files.\n";
} catch (\Exception $e) {
    echo "Connection Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
}
