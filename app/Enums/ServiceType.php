<?php

namespace App\Enums;

enum ServiceType: string
{
    case RECURRING = 'recurring';
    case HOURLY = 'hourly';

    public function label(): string
    {
        return match($this) {
            self::RECURRING => 'Servicio Recurrente',
            self::HOURLY => 'Por Horas',
        };
    }
}