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
        $originalName = $file->getClientOriginalName() ?: 'upload';
        $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename     = $folder . '/' . $safeName . '-' . now()->format('YmdHis') . ($extension ? ".{$extension}" : '');

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
    public static function delete(string $path): bool
    {
        if (Storage::disk('r2')->exists($path)) {
            return Storage::disk('r2')->delete($path);
        }

        return false;
    }
}
