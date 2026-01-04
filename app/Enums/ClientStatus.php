<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ClientStatus: string implements HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLACKLISTED = 'blacklisted';

    public function getLabel(): ?string
    {
        return match($this) {
            self::ACTIVE => 'Activo',
            self::INACTIVE => 'Inactivo',
            self::BLACKLISTED => 'Lista Negra',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'gray',
            self::BLACKLISTED => 'danger',
        };
    }
}