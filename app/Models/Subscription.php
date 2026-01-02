<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'service_id',
        'custom_price',
        'billing_cycle',
        'started_at',
        'cancelled_at',
        'status',
    ];

    protected $casts = [
        'billing_cycle' => BillingCycle::class,
        'status' => SubscriptionStatus::class,
        'custom_price' => 'decimal:2',
        'started_at' => 'date',
        'cancelled_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Subscription $subscription) {
            
            // Regla 1: Estado Activo o Pausado
            if (in_array($subscription->status, [SubscriptionStatus::ACTIVE, SubscriptionStatus::PAUSED])) {
                 if (! $subscription->isForceDeleting()) {
                     \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Operación Bloqueada')
                        ->body('No se puede eliminar una suscripción Activa o Pausada. Cancélela primero.')
                        ->send();
                     return false;
                }
            }

            // Regla 2: Historia Financiera
            if ($subscription->periods()->whereNotNull('invoice_id')->exists()) {
                 if (! $subscription->isForceDeleting()) {
                     \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Operación Bloqueada')
                        ->body('No se puede eliminar una suscripción con historial de facturación.')
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function invoiceItems(): MorphMany
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    public function periods(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubscriptionPeriod::class);
    }

    // Accessor para obtener el precio efectivo
    public function getEffectivePriceAttribute()
    {
        return $this->custom_price ?? ($this->service?->base_price ?? 0);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', SubscriptionStatus::ACTIVE);
    }
}