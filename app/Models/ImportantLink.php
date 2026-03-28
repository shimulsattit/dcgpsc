<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantLink extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url', 'order', 'is_active', 'menu_id'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (ImportantLink $link) {
            // If linked to a menu, always sync title & url from that menu
            if ($link->menu_id && $link->menu) {
                $link->title = $link->menu->title;
                $link->url = $link->menu->url;
            }
        });
    }
}
