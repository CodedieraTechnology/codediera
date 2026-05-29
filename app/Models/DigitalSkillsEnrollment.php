<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSkillsEnrollment extends Model
{
    protected $fillable = [
        'digital_skills_item_id',
        'name',
        'email',
        'phone',
        'message',
        'amount',
        'currency',
        'payment_status',
        'paid_at',
        'payment_reference',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(DigitalSkillsItem::class, 'digital_skills_item_id');
    }
}
