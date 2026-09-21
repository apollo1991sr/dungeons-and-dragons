<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ItemRarity: string implements HasLabel, HasColor
{
    case Common = 'common';
    case Uncommon = 'uncommon';
    case Rare = 'rare';
    case VeryRare = 'very-rare';
    case Legend = 'legend';

    public function label(): string
    {
        return match ($this) {
            self::Common => 'Звичайне',
            self::Uncommon => 'Незвичне',
            self::Rare => 'Рідкісне',
            self::VeryRare => 'Дуже рідкісне',
            self::Legend => 'Легендарне',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Common => 'gray',
            self::Uncommon => 'success',
            self::Rare => 'info',
            self::VeryRare => 'primary',
            self::Legend => 'warning',
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Common => 'Звичайне',
            self::Uncommon => 'Незвичне',
            self::Rare => 'Рідкісне',
            self::VeryRare => 'Дуже рідкісне',
            self::Legend => 'Легендарне',
        };
    }
}
