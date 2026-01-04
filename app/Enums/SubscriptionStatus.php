<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubscriptionStatus: string implements HasLabel
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match($this) {
            self::ACTIVE => 'Activa',
            self::PAUSED => 'Pausada',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::PAUSED => 'warning',
            self::CANCELLED => 'danger',
        };
    }
}