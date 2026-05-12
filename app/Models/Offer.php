<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Support\Facades\Cache;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_url', 'link', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Auto-clear cache on save or delete
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('homepage.offers');
        });

        static::deleted(function () {
            Cache::forget('homepage.offers');
        });
    }

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

    /**
     * Scope to get only active offers
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
