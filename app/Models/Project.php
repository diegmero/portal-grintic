<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'technologies',
        'infrastructure',
        'technical_notes',
        'total_budget',
        'status',
        'started_at',
        'completed_at',
        'deadline',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'total_budget' => 'decimal:2',
        'started_at' => 'date',
        'completed_at' => 'date',
        'deadline' => 'date',
        'technologies' => 'array',
        'infrastructure' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            if ($project->invoiceItems()->exists() || $project->workLogs()->exists()) {
                if (! $project->isForceDeleting()) {
                    // Block deletion silently - UI handles notification
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

    public function invoiceItems(): MorphMany
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class)->orderBy('order');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectNote::class)->latest();
    }

    public function links(): HasMany
    {
        return $this->hasMany(ProjectLink::class);
    }

    public function documentation(): HasMany
    {
        return $this->hasMany(ProjectDocumentation::class)->latest();
    }
    
    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class)->latest();
    }

    /**
     * Get invoices through invoice items (polymorphic)
     */
    public function invoices()
    {
        return $this->hasManyThrough(
            \App\Models\Invoice::class,
            \App\Models\InvoiceItem::class,
            'itemable_id', // Foreign key on invoice_items
            'id', // Foreign key on invoices
            'id', // Local key on projects
            'invoice_id' // Local key on invoice_items
        )->where('invoice_items.itemable_type', \App\Models\Project::class);
    }

    // Accessors
    public function getProgressAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return $this->status === ProjectStatus::DONE ? 100 : 0;
        }
        
        $completedTasks = $this->tasks()->where('status', TaskStatus::COMPLETED)->count();
        
        return (int) round(($completedTasks / $totalTasks) * 100);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline) {
            return false;
        }
        
        return $this->deadline->isPast() && $this->status !== ProjectStatus::DONE;
    }

    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }
        
        return now()->startOfDay()->diffInDays($this->deadline, false);
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

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('deadline')
            ->whereDate('deadline', '<', now())
            ->where('status', '!=', ProjectStatus::DONE);
    }

    public function scopeUpcomingDeadline($query, int $days = 7)
    {
        return $query->whereNotNull('deadline')
            ->whereDate('deadline', '>=', now())
            ->whereDate('deadline', '<=', now()->addDays($days))
            ->where('status', '!=', ProjectStatus::DONE);
    }
}