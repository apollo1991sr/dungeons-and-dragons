<?php

namespace App\Enums;

enum ItemRarity: string
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
}
