<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Menu;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image_url',
        'content',
        'html_content',
        'buttons',
        'bottom_content',
        'meta_title',
        'meta_description',
        'is_published',
        'menu_id',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'buttons' => 'array',
    ];

    /**
     * Relationship with Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relationship with Notices (reverse)
     */
    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the first notice associated with this page
     */
    public function notice()
    {
        return $this->hasOne(Notice::class);
    }

    /**
     * Auto-generate slug from title
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        // Sync Menu relationship
        static::saved(function ($page) {
            if ($page->isDirty('menu_id')) {
                // If menu_id was changed or removed
                $oldMenuId = $page->getOriginal('menu_id');
                if ($oldMenuId) {
                    // Remove page link from old menu
                    $oldMenu = Menu::find($oldMenuId);
                    if ($oldMenu) {
                        $oldMenu->page_id = null;
                        $oldMenu->save();
                    }
                }

                if ($page->menu_id) {
                    // Link new menu to this page
                    $menu = Menu::find($page->menu_id);
                    if ($menu) {
                        $menu->page_id = $page->id;
                        $menu->url = null; // Clear manual URL to prefer page link
                        $menu->save();
                    }
                }
            }
        });
    }

    /**
     * Scope for published pages
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }
}
