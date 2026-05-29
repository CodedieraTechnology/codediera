<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'primary_color',
        'heading_color',
        'logo_path',
        'favicon_path',
        'meta_description',
        'home_hero_kicker',
        'home_hero_title',
        'home_hero_body',
        'home_hero_item1_title',
        'home_hero_item1_body',
        'home_hero_item2_title',
        'home_hero_item2_body',
        'home_hero_item3_title',
        'home_hero_item3_body',
        'footer_text',
        'copyright_text',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_linkedin',
        'social_whatsapp',
        'google_review_url',
        'google_places_api_key',
        'google_place_id',
        'google_places_ssl_verify',
    ];

    protected $casts = [
        'google_places_ssl_verify' => 'boolean',
    ];
}
