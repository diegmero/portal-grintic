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

    protected static function booted(): void
    {
        static::deleting(function (Invoice $invoice) {
            // Revertir estados de períodos vinculados
            foreach ($invoice->invoiceItems as $item) {
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
                // Nota: Proyectos no cambian de estado al eliminar factura
            }
            
            // Eliminar items de la factura para evitar datos huérfanos
            $invoice->invoiceItems()->delete();
            
            // Eliminar pagos asociados
            $invoice->payments()->delete();
        });

        static::updated(function (Invoice $invoice) {
            // Sincronizar estado si la factura fue modificada
            if ($invoice->wasChanged('status')) {
                $invoice->syncRelatedPeriodStatuses();
            }
        });
    }

    /**
     * Verificar si tiene períodos de suscripción vinculados
     */
    public function hasLinkedPeriods(): bool
    {
        return $this->invoiceItems()
            ->where('itemable_type', \App\Models\SubscriptionPeriod::class)
            ->whereNotNull('itemable_id')
            ->exists();
    }

    // Relaciones
    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(self::class, 'id', 'id');
    }

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
    // Calcular totales
    public function calculateTotals()
    {
        $this->subtotal = $this->invoiceItems->sum('subtotal');
        $this->tax_amount = $this->subtotal * ($this->tax_percentage / 100);
        $this->total = $this->subtotal + $this->tax_amount;
        
        // Verificar estados automáticos basado en pagos
        $paid = $this->payments()->sum('amount');
        
        // Si no está cancelada/borrador y hay pagos parciales
        // Si no está cancelada/borrador y hay pagos parciales
        if (!in_array($this->status, [\App\Enums\InvoiceStatus::DRAFT, \App\Enums\InvoiceStatus::CANCELLED])) {
            $diff = round($this->total - $paid, 2);
            
            if ($diff <= 0 && $this->total > 0) {
                $this->status = \App\Enums\InvoiceStatus::PAID;
            } elseif ($paid > 0 && $diff > 0) {
                $this->status = \App\Enums\InvoiceStatus::PARTIALLY_PAID;
            } elseif ($paid == 0 && $this->status === \App\Enums\InvoiceStatus::PAID) {
                // Si estaba pagada pero borraron pagos y quedó en 0
                $this->status = \App\Enums\InvoiceStatus::INVOICED;
            }
        }
        
        $this->save();
        
        // Sincronizar siempre
        $this->syncRelatedPeriodStatuses();
    }

    // Verificar si está completamente pagada
    public function isPaid(): bool
    {
        return $this->payments()->sum('amount') >= $this->total;
    }

    /**
     * Sincronizar el estado de los períodos relacionados
     */
    /**
     * Sincronizar el estado de los items relacionados (Periodos y WorkLogs)
     */
    public function syncRelatedPeriodStatuses(): void
    {
        $this->syncRelatedItemStatuses();
    }

    public function syncRelatedItemStatuses(): void
    {
        // Determinar status base
        $periodStatus = match($this->status) {
            \App\Enums\InvoiceStatus::PAID => \App\Enums\SubscriptionPeriodStatus::PAID,
            \App\Enums\InvoiceStatus::PARTIALLY_PAID => \App\Enums\SubscriptionPeriodStatus::PARTIALLY_PAID,
            \App\Enums\InvoiceStatus::CANCELLED => \App\Enums\SubscriptionPeriodStatus::CANCELLED,
            default => \App\Enums\SubscriptionPeriodStatus::INVOICED,
        };

        $workLogStatus = match($this->status) {
            \App\Enums\InvoiceStatus::PAID => \App\Enums\WorkLogStatus::PAID,
            \App\Enums\InvoiceStatus::PARTIALLY_PAID => \App\Enums\WorkLogStatus::PARTIALLY_PAID,
            // WorkLogs no tienen CANCELLED, volvemos a INVOICED o PENDING?
            // Si la factura se cancela, el trabajo sigue hecho, quizás volver a PENDING para poder facturar de nuevo?
            \App\Enums\InvoiceStatus::CANCELLED => \App\Enums\WorkLogStatus::PENDING, 
            default => \App\Enums\WorkLogStatus::INVOICED,
        };

        // Recorrer items
        foreach ($this->invoiceItems as $item) {
            if ($item->itemable_type === \App\Models\SubscriptionPeriod::class && $item->itemable_id) {
                $period = \App\Models\SubscriptionPeriod::find($item->itemable_id);
                if ($period) {
                    $period->status = $periodStatus;
                    $period->save();
                }
            } elseif ($item->itemable_type === \App\Models\WorkLog::class && $item->itemable_id) {
                $workLog = \App\Models\WorkLog::find($item->itemable_id);
                if ($workLog) {
                    $workLog->status = $workLogStatus;
                    $workLog->save();
                }
            }
        }
    }

    /**
     * Marcar factura como pagada y sincronizar períodos
     */
    public function markAsPaid(): void
    {
        $this->status = \App\Enums\InvoiceStatus::PAID;
        $this->save();
        $this->syncRelatedPeriodStatuses();
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [
            InvoiceStatus::INVOICED,
            InvoiceStatus::OVERDUE
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', InvoiceStatus::INVOICED)
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