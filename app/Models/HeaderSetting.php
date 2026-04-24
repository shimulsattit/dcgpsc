<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GoogleDriveHelper;

class HeaderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phones',
        'facebook_url',
        'youtube_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'show_login_link',
        'logo_url',
        'favicon_url',
        'site_name',
        'site_name_font_size',
        'site_name_bangla',
        'eiin',
        'action_buttons',
        'show_notice_ticker',
        'notice_ticker_label',
        'notice_ticker_limit',
        'ticker_position',
        'is_shop_enabled',
        'show_top_bar',
    ];

    protected $casts = [
        'show_login_link' => 'boolean',
        'show_notice_ticker' => 'boolean',
        'is_shop_enabled' => 'boolean',
        'show_top_bar' => 'boolean',
        'phones' => 'array',
        'action_buttons' => 'array',
        'notice_ticker_limit' => 'integer',
    ];

    /**
     * Get the logo URL with Google Drive conversion
     */
    public function getLogoAttribute()
    {
        if (empty($this->logo_url)) {
            return null;
        }

        if (GoogleDriveHelper::isGoogleDriveUrl($this->logo_url)) {
            return GoogleDriveHelper::getDirectUrl($this->logo_url);
        }

        return $this->logo_url;
    }

    /**
     * Get the favicon URL with Google Drive conversion
     */
    public function getFaviconAttribute()
    {
        if (empty($this->favicon_url)) {
            return null;
        }

        if (GoogleDriveHelper::isGoogleDriveUrl($this->favicon_url)) {
            return GoogleDriveHelper::getDirectUrl($this->favicon_url);
        }

        return $this->favicon_url;
    }
}
