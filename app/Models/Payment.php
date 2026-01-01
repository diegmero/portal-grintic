<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'attachment_path',
        'notes',
    ];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relaciones
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Al crear un pago, actualizar estado de factura
    protected static function booted()
    {
        static::created(function ($payment) {
            $invoice = $payment->invoice;
            
            if ($invoice->isPaid()) {
                $invoice->status = \App\Enums\InvoiceStatus::PAID;
                $invoice->save();
            }
        });
    }
}