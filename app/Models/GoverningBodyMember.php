<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;

class GoverningBodyMember extends Model
{
    use HasFactory;

    protected $table = 'governing_body_members';

    protected $fillable = [
        'name',
        'designation',
        'description',
        'photo_url',
        'role_id',
        'order',
        'is_active',
    ];

    public function role()
    {
        return $this->belongsTo(GoverningBodyRole::class, 'role_id');
    }

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the photo URL with Google Drive conversion
     */
    public function getPhotoUrlAttribute($value)
    {
        if (empty($value))
            return null;

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
