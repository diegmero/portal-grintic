<?php

namespace App\Enums;

enum AssetType: string
{
    case WORDPRESS = 'wordpress';
    case LARAVEL = 'laravel';
    case EMAIL = 'email';
    case SERVER = 'server';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::WORDPRESS => 'WordPress',
            self::LARAVEL => 'Laravel',
            self::EMAIL => 'Email',
            self::SERVER => 'Servidor',
            self::OTHER => 'Otro',
        };
    }
}