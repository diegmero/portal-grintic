<?php

namespace App\Enums;

enum ReportType: string
{
    case MONTHLY_SUMMARY = 'monthly_summary';
    case PROJECT_REPORT = 'project_report';
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match($this) {
            self::MONTHLY_SUMMARY => 'Resumen Mensual',
            self::PROJECT_REPORT => 'Reporte de Proyecto',
            self::CUSTOM => 'Personalizado',
        };
    }
}