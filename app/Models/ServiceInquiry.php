<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceInquiry extends Model
{
    public const RENEWAL_GRACE_DAYS = 3;

    protected $fillable = [
        'service_id',
        'order_code',
        'access_key',
        'paystack_customer_code',
        'paystack_authorization_code',
        'paystack_subscription_code',
        'paystack_email_token',
        'paystack_setup_reference',
        'name',
        'email',
        'phone',
        'company',
        'budget',
        'message',
        'meta',
        'payment_type',
        'amount',
        'currency',
        'payment_status',
        'paid_at',
        'next_renewal_at',
        'status',
        'progress_percent',
        'progress_note',
        'grace_trial_enabled',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'next_renewal_at' => 'datetime',
        'progress_percent' => 'integer',
        'grace_trial_enabled' => 'boolean',
        'meta' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payments()
    {
        return $this->hasMany(ServiceInquiryPayment::class)->orderByDesc('id');
    }

    public function isRenewable(): bool
    {
        return in_array($this->payment_type, ['monthly', 'yearly'], true);
    }

    public function renewalGraceEndsAt()
    {
        if (!$this->next_renewal_at) return null;
        if (!$this->grace_trial_enabled) {
            return $this->next_renewal_at->copy();
        }
        return $this->next_renewal_at->copy()->addDays(self::RENEWAL_GRACE_DAYS);
    }

    public function isInGracePeriod(): bool
    {
        $graceEnd = $this->renewalGraceEndsAt();
        if (!$graceEnd) return false;
        return now()->greaterThan($this->next_renewal_at) && now()->lessThanOrEqualTo($graceEnd);
    }

    public function isExpiredBeyondGrace(): bool
    {
        $graceEnd = $this->renewalGraceEndsAt();
        if (!$graceEnd) return false;
        return now()->greaterThan($graceEnd);
    }
}
