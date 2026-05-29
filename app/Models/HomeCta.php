<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCta extends Model
{
    protected $fillable = [
        'slug',
        'heading',
        'body',
        'button_text',
        'button_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

