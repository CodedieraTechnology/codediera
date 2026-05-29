<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $fillable = [
        'title',
        'location',
        'employment_type',
        'description',
        'requirements',
        'responsibilities',
        'salary',
        'posted_at',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'posted_at' => 'datetime',
    ];
}

