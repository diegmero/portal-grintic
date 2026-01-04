<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceType: string implements HasLabel
{
    case RECURRING = 'recurring';
    case HOURLY = 'hourly';

    public function getLabel(): ?string
    {
        return match($this) {
            self::RECURRING => 'Servicio Recurrente',
            self::HOURLY => 'Por Horas',
        };
    }
}