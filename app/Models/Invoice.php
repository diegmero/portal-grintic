<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Calcular totales
    public function calculateTotals()
    {
        $this->subtotal = $this->invoiceItems->sum('subtotal');
        $this->tax_amount = $this->subtotal * ($this->tax_percentage / 100);
        $this->total = $this->subtotal + $this->tax_amount;
        $this->save();
    }

    // Verificar si está completamente pagada
    public function isPaid(): bool
    {
        return $this->payments()->sum('amount') >= $this->total;
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [
            InvoiceStatus::SENT,
            InvoiceStatus::OVERDUE
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', InvoiceStatus::SENT)
                     ->whereDate('due_date', '<', now());
    }

    // Generar número de factura automático
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        
        // Usar lock para evitar race conditions
        $lastInvoice = self::whereYear('created_at', $year)
                          ->lockForUpdate()
                          ->orderBy('id', 'desc')
                          ->first();

        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -3) + 1 : 1;

        return sprintf('INV-%d-%03d', $year, $number);
    }
}