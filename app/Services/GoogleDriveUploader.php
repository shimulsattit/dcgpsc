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
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            throw new \RuntimeException('Google Drive OAuth credentials are not set in .env.');
        }

        $client = new GoogleClient();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setScopes([GoogleDriveService::DRIVE]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Fetch new access token using refresh token
        $client->refreshToken($refreshToken);
        
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
        try {
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
        } catch (\Exception $e) {
            \Log::warning("Could not set permissions for Drive file: " . $e->getMessage());
        }

        // Fetch the webViewLink after permissions are applied
        $fetched = $service->files->get($created->id, [
            'fields' => 'webViewLink',
        ]);

        if (! $fetched->webViewLink) {
            return "https://drive.google.com/file/d/{$created->id}/view";
        }

        return $fetched->webViewLink;
    }

    /**
     * Delete a file from Google Drive.
     */
    public static function delete(string $fileUrl): bool
    {
        // Extract file ID from URL
        // Patterns: 
        // 1. https://drive.google.com/file/d/FILE_ID/view
        // 2. https://drive.google.com/uc?id=FILE_ID
        // 3. https://docs.google.com/file/d/FILE_ID/edit
        
        $fileId = null;
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $fileUrl, $matches)) {
            $fileId = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $fileUrl, $matches)) {
            $fileId = $matches[1];
        }

        if (!$fileId) {
            return false;
        }

        try {
            $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
            $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
            $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');

            $client = new GoogleClient();
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            $client->refreshToken($refreshToken);
            
            $service = new GoogleDriveService($client);
            $service->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            \Log::error("Google Drive Delete failed for {$fileId}: " . $e->getMessage());
            return false;
        }
    }
}
