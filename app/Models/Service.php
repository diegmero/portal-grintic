<?php

namespace App\Models;

use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'base_price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'type' => ServiceType::class,
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRecurring($query)
    {
        return $query->where('type', ServiceType::RECURRING);
    }

    public function scopeHourly($query)
    {
        return $query->where('type', ServiceType::HOURLY);
    }
}