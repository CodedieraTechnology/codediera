<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'heading',
        'body',
        'address',
        'phone',
        'email',
        'map_embed_url',
    ];

    public function getMapEmbedUrlAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }

    public function setMapEmbedUrlAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['map_embed_url'] = null;
            return;
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $matches)) {
            $this->attributes['map_embed_url'] = $matches[1];
            return;
        }

        $this->attributes['map_embed_url'] = $value;
    }
}

