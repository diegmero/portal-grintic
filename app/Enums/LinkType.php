<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LinkType: string implements HasLabel, HasColor, HasIcon
{
    case GIT = 'git';
    case FIGMA = 'figma';
    case DOCS = 'docs';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::GIT => 'Repositorio Git',
            self::FIGMA => 'Figma',
            self::DOCS => 'Documentación',
            self::OTHER => 'Otro',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::GIT => 'danger',
            self::FIGMA => 'info',
            self::DOCS => 'success',
            self::OTHER => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::GIT => 'heroicon-o-code-bracket',
            self::FIGMA => 'heroicon-o-paint-brush',
            self::DOCS => 'heroicon-o-document-text',
            self::OTHER => 'heroicon-o-link',
        };
    }
}
