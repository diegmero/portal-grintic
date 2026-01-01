<?php

namespace App\Enums;

enum WorkLogStatus: string
{
    case PENDING = 'pending';
    case INVOICED = 'invoiced';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::INVOICED => 'Facturado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::INVOICED => 'success',
        };
    }
}