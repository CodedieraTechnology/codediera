<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSkillsLesson extends Model
{
    protected $fillable = [
        'digital_skills_item_id',
        'title',
        'brief_info',
        'content',
        'video_url',
        'pdf_path',
        'image_path',
        'is_preview',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(DigitalSkillsItem::class, 'digital_skills_item_id');
    }
}
