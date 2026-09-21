<?php

namespace App\Enums;

enum ItemAction: string
{
    case Action = 'action';
    case BonusAction = 'bonus-action';
    case Reaction = 'reaction';

    public function label(): string
    {
        return match ($this) {
            self::Action => 'Дія',
            self::BonusAction => 'Додаткова дія',
            self::Reaction => 'Реагування',
        };
    }

    public function icon(): string
    {
        return $this->value;
    }
}
