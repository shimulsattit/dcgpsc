<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;

class PhotoGallery extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'published_at' => 'date',
    ];

    /**
     * Get the display thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        // Priority 1: Custom R2 Thumbnail URL
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        // Priority 2: Use first image from the gallery (images array)
        if (!empty($this->images) && is_array($this->images)) {
            $firstImage = $this->images[0] ?? null;
            if ($firstImage) {
                // Check if it's an R2 URL or a local path
                return str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
            }
        }

        // Priority 3: Legacy Manual uploaded thumbnail
        if (!empty($this->manual_thumbnail)) {
            return asset('storage/' . $this->manual_thumbnail);
        }

        // Priority 4: Legacy Google Drive image ID
        if (!empty($this->thumbnail_image_id)) {
            $id = GoogleDriveHelper::extractFileId($this->thumbnail_image_id);

            if ($id) {
                return "https://drive.google.com/thumbnail?id={$id}&sz=w1920";
            }
        }

        return null;
    }

    /**
     * Get the image URL from either uploaded file or Google Drive link
     */
    public static function getImageUrl($imageData)
    {
        if (isset($imageData['source']) && $imageData['source'] === 'drive' && !empty($imageData['drive_link'])) {
            return GoogleDriveHelper::getDirectUrl($imageData['drive_link']);
        }

        // Return uploaded image path
        return isset($imageData['image']) ? asset('storage/' . $imageData['image']) : null;
    }
}
