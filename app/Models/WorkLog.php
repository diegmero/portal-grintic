<?php

namespace App\Models;

use App\Enums\WorkLogStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'project_id',
        'service_id',
        'hours',
        'hourly_rate',
        'description',
        'worked_at',
        'status',
        'attachment_path',
    ];

    protected $casts = [
        'status' => WorkLogStatus::class,
        'hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'worked_at' => 'date',
        'attachment_path' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleting(function (WorkLog $workLog) {
            if ($workLog->status !== WorkLogStatus::PENDING) {
                 if (! $workLog->isForceDeleting()) {
                     \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Operación Bloqueada')
                        ->body('No se puede eliminar un registro que ya ha sido facturado o pagado. Debes anular la factura primero.')
                        ->send();
                        
                     return false;
                }
            }
        });
    }

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function invoiceItems(): MorphMany
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    public function invoiceItem(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(InvoiceItem::class, 'itemable')->latestOfMany();
    }

    // Accessor para calcular total
    public function getTotalAttribute()
    {
        return $this->hours * $this->hourly_rate;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', WorkLogStatus::PENDING);
    }

    public function scopeInvoiced($query)
    {
        return $query->where('status', WorkLogStatus::INVOICED);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}