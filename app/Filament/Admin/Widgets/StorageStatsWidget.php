<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class StorageStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // R2 Stats (Cache 5 minutes)
        $r2 = Cache::remember('r2_storage_stats_v7', 300, function () {
            try {
                $files = Storage::disk('r2')->allFiles();
                $totalSize = 0;
                foreach ($files as $file) {
                    $totalSize += Storage::disk('r2')->size($file);
                }
                return ['count' => count($files), 'size' => $this->formatBytes($totalSize)];
            } catch (\Exception $e) {
                return ['count' => '?', 'size' => 'Fetching...'];
            }
        });

        // GDrive Stats (Cache 5 minutes)
        $gdrive = Cache::remember('gdrive_storage_stats_v8', 300, function () {
            try {
                $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
                $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
                $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
                $folderId = env('GOOGLE_DRIVE_FOLDER_ID');

                if (!$clientId || !$clientSecret || !$refreshToken) {
                    return ['count' => 0, 'size' => '0 B'];
                }

                $client = new \Google\Client();
                $client->setClientId($clientId);
                $client->setClientSecret($clientSecret);
                $client->refreshToken($refreshToken);
                $service = new \Google\Service\Drive($client);

                $query = "'$folderId' in parents and trashed = false";
                $files = $service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id, size)',
                ]);

                $totalSize = 0;
                $count = 0;
                foreach ($files->getFiles() as $file) {
                    $totalSize += (int)($file->size ?? 0);
                    $count++;
                }

                return ['count' => $count, 'size' => $this->formatBytes($totalSize)];
            } catch (\Exception $e) {
                \Log::error("GDrive Stats Error: " . $e->getMessage());
                return ['count' => '?', 'size' => 'Error'];
            }
        });

        return [
            Stat::make('R2 Storage Status', $r2['size'])
                ->description($r2['count'] . ' Total Files Stored')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('info'),

            Stat::make('Google Drive Status', $gdrive['size'])
                ->description($gdrive['count'] . ' Large Files (>10MB)')
                ->descriptionIcon('heroicon-m-cloud-arrow-up')
                ->color('primary'),
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
