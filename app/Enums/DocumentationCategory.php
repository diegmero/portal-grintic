<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DocumentationCategory: string implements HasLabel, HasColor
{
    case ARCHITECTURE = 'architecture';
    case API = 'api';
    case DEPLOYMENT = 'deployment';
    case USER_GUIDE = 'user_guide';
    case REQUIREMENTS = 'requirements';
    case DELIVERY_ACT = 'delivery_act';
    case LINKS = 'links';
    case NOTES = 'notes';
    case CREDENTIALS = 'credentials';
    case MEETINGS = 'meetings';
    case CHANGELOG = 'changelog';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::ARCHITECTURE => 'Arquitectura',
            self::API => 'API',
            self::DEPLOYMENT => 'Despliegue',
            self::USER_GUIDE => 'Guía de Usuario',
            self::REQUIREMENTS => 'Requerimientos',
            self::DELIVERY_ACT => 'Acta de Entrega',
            self::LINKS => 'Enlaces',
            self::NOTES => 'Notas',
            self::CREDENTIALS => 'Credenciales',
            self::MEETINGS => 'Reuniones',
            self::CHANGELOG => 'Registro de Cambios',
            self::OTHER => 'Otro',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ARCHITECTURE => 'info',
            self::API => 'info',
            self::DEPLOYMENT => 'info',
            self::USER_GUIDE => 'info',
            self::REQUIREMENTS => 'info',
            self::DELIVERY_ACT => 'info',
            self::LINKS => 'info',
            self::NOTES => 'info',
            self::CREDENTIALS => 'info',
            self::MEETINGS => 'info',
            self::CHANGELOG => 'info',
            self::OTHER => 'info',
        };
    }
}
