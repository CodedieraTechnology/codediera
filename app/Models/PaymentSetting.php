<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'paystack_enabled',
        'paystack_public_key',
        'paystack_secret_key',
        'trial_days',
        'paystack_auth_amount_kobo',
    ];

    protected $casts = [
        'paystack_enabled' => 'boolean',
        'trial_days' => 'integer',
        'paystack_auth_amount_kobo' => 'integer',
    ];
}

