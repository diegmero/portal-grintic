<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum WorkLogStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case INVOICED = 'invoiced';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';

    public function getLabel(): ?string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::INVOICED => 'Facturado',
            self::PAID => 'Pagado',
            self::PARTIALLY_PAID => 'Pago Parcial',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'warning',
            self::INVOICED => 'info',
            self::PAID => 'success',
            self::PARTIALLY_PAID => 'warning',
        };
    }
}