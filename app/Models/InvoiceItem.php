<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
        'itemable_type',
        'itemable_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    // Calcular subtotal automáticamente antes de guardar
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::deleting(function ($item) {
            if ($item->itemable_type === \App\Models\SubscriptionPeriod::class && $item->itemable_id) {
                $period = \App\Models\SubscriptionPeriod::find($item->itemable_id);
                if ($period) {
                    $period->invoice_id = null;
                    $period->status = \App\Enums\SubscriptionPeriodStatus::PENDING;
                    $period->save();
                }
            } elseif ($item->itemable_type === \App\Models\WorkLog::class && $item->itemable_id) {
                $workLog = \App\Models\WorkLog::find($item->itemable_id);
                if ($workLog) {
                    $workLog->status = \App\Enums\WorkLogStatus::PENDING;
                    $workLog->save();
                }
            }
        });
    }
}