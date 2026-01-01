<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'total_budget',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'total_budget' => 'decimal:2',
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoiceItems(): MorphMany
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            ProjectStatus::PLANNING,
            ProjectStatus::DEVELOPMENT,
            ProjectStatus::QA
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', ProjectStatus::DONE);
    }
}