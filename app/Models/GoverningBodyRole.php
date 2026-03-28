<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoverningBodyRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_font_size',
        'name_color',
        'designation_font_size',
        'designation_color',
        'badge_bg_color',
        'badge_text_color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function members()
    {
        return $this->hasMany(GoverningBodyMember::class, 'role_id');
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
