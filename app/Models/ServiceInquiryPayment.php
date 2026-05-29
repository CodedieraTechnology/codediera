<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceInquiryPayment extends Model
{
    protected $fillable = [
        'service_inquiry_id',
        'amount',
        'currency',
        'status',
        'reference',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function inquiry()
    {
        return $this->belongsTo(ServiceInquiry::class, 'service_inquiry_id');
    }
}

