<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasLabel, HasColor
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::SENT => 'Enviada',
            self::PAID => 'Pagado',
            self::PARTIALLY_PAID => 'Pago Parcial',
            self::OVERDUE => 'Vencida',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::SENT => 'info',
            self::PAID => 'success',
            self::PARTIALLY_PAID => 'warning',
            self::OVERDUE => 'danger',
            self::CANCELLED => 'warning',
        };
    }
}