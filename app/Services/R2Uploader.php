<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class R2Uploader
{
    /**
     * Upload a file to Cloudflare R2 and return the public URL.
     */
    public static function uploadAndGetUrl(TemporaryUploadedFile $file, string $folder = 'uploads'): ?string
    {
        // 10 MB = 10 * 1024 * 1024 bytes
        $maxR2Size = 10 * 1024 * 1024;

        if (!$file->isValid()) {
            \Log::error("File is not valid");
            return null;
        }

        try {
            if ($file->getSize() > $maxR2Size) {
                return GoogleDriveUploader::uploadAndGetShareLink($file);
            }
        } catch (\Exception $e) {
            \Log::error("Metadata retrieval failed: " . $e->getMessage());
            // If we can't get size, assume it's small or just try to upload to R2 anyway
        }

        $originalName = $file->getClientOriginalName() ?: 'upload';
        $filenameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        // Ensure we have a safe filename, even for Bengali/Unicode names
        $safeName = Str::slug($filenameOnly);
        if (empty($safeName)) {
            $safeName = 'upload-' . Str::random(8);
        }
        
        $filename = $folder . '/' . $safeName . '-' . now()->format('YmdHis') . ($extension ? ".{$extension}" : '');

        try {
            $uploaded = Storage::disk('r2')->put(
                $filename,
                file_get_contents($file->getRealPath()),
                'public'
            );

            if (!$uploaded) {
                \Log::error("R2 Upload failed for: {$filename}");
                return null;
            }

            return static::getPublicUrl($filename);
        } catch (\Exception $e) {
            \Log::error("R2 Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the public URL of a file stored in R2.
     */
    public static function getPublicUrl(string $path): string
    {
        $r2Url = rtrim(config('filesystems.disks.r2.url'), '/');

        if ($r2Url) {
            return $r2Url . '/' . ltrim($path, '/');
        }

        // Fallback: use the R2 endpoint + bucket
        $endpoint = rtrim(config('filesystems.disks.r2.endpoint'), '/');
        $bucket   = config('filesystems.disks.r2.bucket');

        return "{$endpoint}/{$bucket}/{$path}";
    }

    /**
     * Delete a file from R2.
     */
    public static function delete(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        // If it's a Google Drive URL, delete from Drive
        if (str_contains($path, 'drive.google.com') || str_contains($path, 'docs.google.com')) {
            return GoogleDriveUploader::delete($path);
        }

        // Otherwise assume it's an R2 path
        // Extract relative path from full URL if needed
        $r2Url = config('filesystems.disks.r2.url');
        if ($r2Url && str_starts_with($path, $r2Url)) {
            $path = ltrim(str_replace($r2Url, '', $path), '/');
        }

        if (Storage::disk('r2')->exists($path)) {
            return Storage::disk('r2')->delete($path);
        }

        return false;
    }
}
