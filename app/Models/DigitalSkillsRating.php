<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSkillsRating extends Model
{
    protected $fillable = [
        'digital_skills_item_id',
        'name',
        'email',
        'rating',
        'comment',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(DigitalSkillsItem::class, 'digital_skills_item_id');
    }
}
