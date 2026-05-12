<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = [
        'homepage_template',
        'is_header_gradient',
        'sidebar_title',
        'news_events_title',
        'achievements_title',
        'all_notices_title',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'link_color',
        'topbar_bg_color',
        'header_bg_color',
        'navbar_bg_color',
        'navbar_hover_color',
        'footer_bg_color',
        'footer_bottom_bg_color',
        'body_bg_color',
        'ticker_bg_color',
        'ticker_text_color',
        'message_card_design',
        'message_card_bg_image',
        'message_card_bg_image_gdrive',
        'card_header_color',
        'card_name_text_color',
        'card_designation_text_color',
        'card_image_border_color',
        'card_button_bg_color',
        'card_button_text_color',
    ];
}
