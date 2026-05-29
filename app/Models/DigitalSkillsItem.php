<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSkillsItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'instructor_user_id',
        'total_hours',
        'is_free',
        'price',
        'currency',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'total_hours' => 'decimal:2',
    ];

    public function enrollments()
    {
        return $this->hasMany(DigitalSkillsEnrollment::class, 'digital_skills_item_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }

    public function lessons()
    {
        return $this->hasMany(DigitalSkillsLesson::class, 'digital_skills_item_id')->orderBy('sort_order')->orderByDesc('id');
    }

    public function ratings()
    {
        return $this->hasMany(DigitalSkillsRating::class, 'digital_skills_item_id')->orderByDesc('id');
    }
}
