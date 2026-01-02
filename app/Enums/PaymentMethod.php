<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case TRANSFERENCIA_BANCARIA = 'bank_transfer';
    case EFECTIVO = 'cash';
    case BOLD_LINK = 'bold';
    case MERCADO_PAGO = 'mercadopago';
    case OTRO = 'other';

    public function getLabel(): ?string
    {
        return match($this) {
            self::TRANSFERENCIA_BANCARIA => 'Transferencia Bancaria',
            self::EFECTIVO => 'Efectivo',
            self::BOLD_LINK => 'Bold',
            self::MERCADO_PAGO => 'Mercado Pago',
            self::OTRO => 'Otro',
        };
    }
}