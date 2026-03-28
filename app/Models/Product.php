<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'price',
        'discount_price',
        'image_url',
        'is_active',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
        'order' => 'integer',
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

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
