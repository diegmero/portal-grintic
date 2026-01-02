<?php

namespace App\Models;

use App\Enums\SubscriptionPeriodStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'invoice_id',
        'period_start',
        'period_end',
        'amount',
        'status',
        'work_description',
        'internal_notes',
    ];

    protected $casts = [
        'status' => SubscriptionPeriodStatus::class,
        'period_start' => 'date',
        'period_end' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relaciones
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', SubscriptionPeriodStatus::PENDING);
    }

    public function scopeInvoiced($query)
    {
        return $query->where('status', SubscriptionPeriodStatus::INVOICED);
    }

    public function scopePaid($query)
    {
        return $query->where('status', SubscriptionPeriodStatus::PAID);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', SubscriptionPeriodStatus::CANCELLED);
    }

    // Accessors
    public function getPeriodLabelAttribute(): string
    {
        $format = match($this->subscription->billing_cycle) {
            \App\Enums\BillingCycle::MONTHLY => 'M Y',
            \App\Enums\BillingCycle::QUARTERLY => 'M Y',
            \App\Enums\BillingCycle::YEARLY => 'Y',
        };

        return $this->period_start->translatedFormat($format);
    }
}
