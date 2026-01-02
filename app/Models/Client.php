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
        'tax_id',
        'internal_notes',
        'status',
    ];

    protected $casts = [
        'status' => ClientStatus::class,
    ];

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