<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = [
        'key',
        'name',
        'schema',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'schema' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}

