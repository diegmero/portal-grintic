<?php

namespace App\Models;

use App\Enums\AssetType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'url',
        'technology',
        'credentials',
        'notes',
    ];

    protected $casts = [
        'technology' => AssetType::class,
    ];

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Accessor para desencriptar credenciales
    public function getCredentialsAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Mutator para encriptar credenciales
    public function setCredentialsAttribute($value)
    {
        $this->attributes['credentials'] = $value ? Crypt::encryptString($value) : null;
    }
}