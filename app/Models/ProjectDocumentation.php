<?php

namespace App\Models;

use App\Enums\DocumentationCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocumentation extends Model
{
    use HasFactory;

    protected $table = 'project_documentation';

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'category',
        'content',
    ];

    protected $casts = [
        'category' => DocumentationCategory::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
