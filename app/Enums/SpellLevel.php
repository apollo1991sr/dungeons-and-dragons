<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SpellLevel: int implements HasLabel
{
    case Cantrip = 0;
    case Level1 = 1;
    case Level2 = 2;
    case Level3 = 3;
    case Level4 = 4;
    case Level5 = 5;
    case Level6 = 6;
    case Level7 = 7;
    case Level8 = 8;
    case Level9 = 9;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Cantrip => 'Замовляння',
            self::Level1 => '1 рівень',
            self::Level2 => '2 рівень',
            self::Level3 => '3 рівень',
            self::Level4 => '4 рівень',
            self::Level5 => '5 рівень',
            self::Level6 => '6 рівень',
            self::Level7 => '7 рівень',
            self::Level8 => '8 рівень',
            self::Level9 => '9 рівень',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }
}
