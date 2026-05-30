<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'enabled',
        'provider',
        'api_key',
        'model',
        'ssl_verify',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'ssl_verify' => 'boolean',
    ];
}
