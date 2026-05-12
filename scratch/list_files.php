<?php
require 'vendor/autoload.php';

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;

$jsonKeyFile = 'storage/app/google/service-account.json';

try {
    $client = new GoogleClient();
    $client->setAuthConfig($jsonKeyFile);
    $client->setScopes([GoogleDriveService::DRIVE]);
    $service = new GoogleDriveService($client);

    $files = $service->files->listFiles(['fields' => 'files(id, name, parents)']);
    foreach ($files->getFiles() as $file) {
        echo "Found File: " . $file->name . " (ID: " . $file->id . ") Parents: " . ($file->parents ? implode(',', $file->parents) : 'none') . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
