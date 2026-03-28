<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'title', 'slug', 'designation', 'image_url', 'message', 'link', 'style', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'style' => 'array',
    ];

    /**
     * Get the image_url attribute with Google Drive URL conversion
     */
    public function getImageUrlAttribute($value)
    {
        if (GoogleDriveHelper::isGoogleDriveUrl($value)) {
            return GoogleDriveHelper::getDirectUrl($value);
        }
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

