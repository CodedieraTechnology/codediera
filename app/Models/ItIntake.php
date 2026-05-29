<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItIntake extends Model
{
    protected $fillable = [
        'student_name',
        'email',
        'phone_number',
        'matriculation_number',
        'institution',
        'department',
        'level',
        'place_of_it',
        'specialization',
        'approval_status',
        'coordinator_signature',
        'coordinator_date',
    ];

    protected $casts = [
        'coordinator_date' => 'date',
    ];
}
