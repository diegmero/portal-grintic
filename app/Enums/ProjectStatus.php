<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasLabel
{
    case PLANNING = 'planning';
    case DEVELOPMENT = 'development';
    case QA = 'qa';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match($this) {
            self::PLANNING => 'Planificación',
            self::DEVELOPMENT => 'Desarrollo',
            self::QA => 'Testing/QA',
            self::DONE => 'Completado',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PLANNING => 'gray',
            self::DEVELOPMENT => 'info',
            self::QA => 'warning',
            self::DONE => 'success',
            self::CANCELLED => 'danger',
        };
    }
}