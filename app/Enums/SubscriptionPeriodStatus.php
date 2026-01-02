<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubscriptionPeriodStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case INVOICED = 'invoiced';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::INVOICED => 'Facturado',
            self::PAID => 'Pagado',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'warning',
            self::INVOICED => 'info',
            self::PAID => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
