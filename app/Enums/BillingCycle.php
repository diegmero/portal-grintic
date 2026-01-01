<?php

namespace App\Enums;

enum BillingCycle: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';

    public function label(): string
    {
        return match($this) {
            self::MONTHLY => 'Mensual',
            self::QUARTERLY => 'Trimestral',
            self::YEARLY => 'Anual',
        };
    }
}