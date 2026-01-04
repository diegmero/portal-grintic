<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Contact extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'portal';
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'password',
        'is_primary',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relaciones
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Mutator para encriptar password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}