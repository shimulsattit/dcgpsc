<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Helpers\GoogleDriveHelper;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_url', 'link', 'order', 'is_active', 'image_position', 'image_fit'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Auto-clear slider cache on save or delete
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('homepage.sliders');
        });

        static::deleted(function () {
            Cache::forget('homepage.sliders');
        });
    }

    /**
     * Get the image_url attribute with Google Drive URL conversion
     */
    public function getImageUrlAttribute($value)
    {
        // Only convert if it's a Google Drive URL
        if (GoogleDriveHelper::isGoogleDriveUrl($value)) {
            return GoogleDriveHelper::getDirectUrl($value);
        }

        return $value;
    }

    /**
     * Scope to get only active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

