<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Support\Str;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'file', 'link', 'page_id', 'published_at', 'expires_at'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relationship with Page
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the file URL attribute with Google Drive URL conversion
     */
    public function getFileUrlAttribute()
    {
        if (empty($this->file)) {
            return null;
        }

        if (GoogleDriveHelper::isGoogleDriveUrl($this->file)) {
            return GoogleDriveHelper::getDirectUrl($this->file);
        }

        return $this->file;
    }

    /**
     * Get the final link URL for the notice
     * Priority: 1. Manual Link, 2. File URL, 3. Linked Page Slug
     */
    public function getLinkUrlAttribute()
    {
        if (!empty($this->link)) {
            return $this->link;
        }

        if (!empty($this->file)) {
            return $this->file_url;
        }

        if ($this->page) {
            return route('page.show', $this->page->slug);
        }

        return '#';
    }

    /**
     * Auto-create page when notice is created
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($notice) {
            // Generate a unique slug (avoid duplicate constraint violation)
            $baseSlug = Str::slug($notice->title) . '-notice';
            $slug = $baseSlug;
            $count = 1;

            while (Page::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            // Create a page for this notice
            $page = Page::create([
                'title' => $notice->title,
                'slug' => $slug,
                'content' => $notice->content ?? '<p>Notice content will be added here.</p>',
                'is_published' => true,
                'published_at' => $notice->published_at,
            ]);

            // Link the page to the notice
            $notice->page_id = $page->id;
            $notice->saveQuietly(); // Save without triggering events
        });

        static::updated(function ($notice) {
            // Update the linked page title if notice title changes
            if ($notice->page && $notice->isDirty('title')) {
                $notice->page->update([
                    'title' => $notice->title,
                ]);
            }
        });
    }
}
