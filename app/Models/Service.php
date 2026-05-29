<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'service_type',
        'description',
        'icon',
        'screenshot_path',
        'approach_image_path',
        'download_url',
        'instructions',
        'delivery_duration_value',
        'delivery_duration_unit',
        'grace_trial_enabled',
        'is_free',
        'price',
        'payment_type',
        'paystack_plan_code_monthly',
        'paystack_plan_code_yearly',
        'inquiry_fields',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'inquiry_fields' => 'array',
        'delivery_duration_value' => 'integer',
        'grace_trial_enabled' => 'boolean',
    ];

    public function inquiries()
    {
        return $this->hasMany(ServiceInquiry::class);
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class)->orderBy('sort_order')->orderByDesc('id');
    }
}
