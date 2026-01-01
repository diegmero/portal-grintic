<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case PAYPAL = 'paypal';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::BANK_TRANSFER => 'Transferencia Bancaria',
            self::CASH => 'Efectivo',
            self::CREDIT_CARD => 'Tarjeta de Crédito',
            self::PAYPAL => 'PayPal',
            self::OTHER => 'Otro',
        };
    }
}