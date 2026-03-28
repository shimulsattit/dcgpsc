<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GoogleDriveUploader
{
    public static function uploadAndGetShareLink(TemporaryUploadedFile $file): string
    {
        $jsonKeyFile = env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON');
        if (! $jsonKeyFile) {
            throw new \RuntimeException('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON is not set.');
        }

        $client = new GoogleClient();
        $client->setAuthConfig($jsonKeyFile);
        $client->setScopes([GoogleDriveService::DRIVE]);
        $client->setApplicationName(config('app.name', 'Laravel Google Drive'));

        $service = new GoogleDriveService($client);

        $folderId = env('GOOGLE_DRIVE_FOLDER_ID') ?: null;

        $originalName = $file->getClientOriginalName() ?: 'upload';
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename = $safeName . '-' . now()->format('YmdHis') . ($extension ? ".{$extension}" : '');

        $driveFile = new DriveFile([
            'name' => $filename,
            'parents' => $folderId ? [$folderId] : null,
        ]);

        $created = $service->files->create(
            $driveFile,
            [
                'data' => file_get_contents($file->getRealPath()),
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink',
            ]
        );

        // Make it accessible via link (anyone with the link can view)
        $service->permissions->create(
            $created->id,
            new Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]),
            [
                'fields' => 'id',
            ]
        );

        // Fetch the webViewLink after permissions are applied
        $fetched = $service->files->get($created->id, [
            'fields' => 'webViewLink',
        ]);

        if (! $fetched->webViewLink) {
            // Fallback to standard share URL format
            return "https://drive.google.com/file/d/{$created->id}/view";
        }

        return $fetched->webViewLink;
    }
}

