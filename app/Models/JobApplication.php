<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_vacancy_id',
        'full_name',
        'email',
        'phone',
        'position',
        'cv_path',
        'message',
        'status',
    ];

    public function vacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }
}
