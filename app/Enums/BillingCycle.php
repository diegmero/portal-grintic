<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingCycle: string implements HasLabel
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';

    public function getLabel(): ?string
    {
        return match($this) {
            self::MONTHLY => 'Mensual',
            self::QUARTERLY => 'Trimestral',
            self::YEARLY => 'Anual',
        };
    }
}