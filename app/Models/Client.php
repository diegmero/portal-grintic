<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'country_code',
        'tax_id_type',
        'tax_id',
        'internal_notes',
        'status',
    ];

    protected $casts = [
        'status' => ClientStatus::class,
    ];

    /**
     * Obtener el nombre del país
     */
    public function getCountryNameAttribute(): ?string
    {
        if (!$this->country_code) {
            return null;
        }
        return config("countries.{$this->country_code}.name");
    }

    /**
     * Obtener el nombre del tipo de ID fiscal
     */
    public function getTaxIdLabelAttribute(): ?string
    {
        if (!$this->country_code || !$this->tax_id_type) {
            return null;
        }
        return config("countries.{$this->country_code}.tax_ids.{$this->tax_id_type}");
    }

    /**
     * Obtener el ID fiscal formateado (tipo + número)
     */
    public function getFormattedTaxIdAttribute(): ?string
    {
        if (!$this->tax_id) {
            return null;
        }
        $prefix = $this->tax_id_type ?: '';
        return $prefix ? "{$prefix}: {$this->tax_id}" : $this->tax_id;
    }


    // Relaciones
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Accessor para contacto principal
    public function primaryContact()
    {
        return $this->contacts()->where('is_primary', true)->first();
    }
}