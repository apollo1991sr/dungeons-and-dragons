<?php

namespace App\Enums;

enum ItemCategory: string
{
    case Equipment = 'equipment';
    case Consumables = 'consumables';

    public function label(): string
    {
        return match ($this) {
            self::Equipment => 'Спорядження',
            self::Consumables => 'Витратники',
        };
    }

    public function icon(): string
    {
        return $this->value;
    }
}
