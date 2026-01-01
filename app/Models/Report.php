<?php

namespace App\Models;

use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'title',
        'file_path',
        'month_year',
        'report_type',
    ];

    protected $casts = [
        'report_type' => ReportType::class,
        'month_year' => 'date',
    ];

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Scopes
    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereYear('month_year', $year)
                     ->whereMonth('month_year', $month);
    }
}