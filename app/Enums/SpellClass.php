<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SpellClass: string implements HasLabel
{
    case Bard = 'bard';
    case Cleric = 'cleric';
    case Druid = 'druid';
    case Paladin = 'paladin';
    case Ranger = 'ranger';
    case Sorcerer = 'sorcerer';
    case Warlock = 'warlock';
    case Wizard = 'wizard';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Bard => 'Бард',
            self::Cleric => 'Клірик',
            self::Druid => 'Друїд',
            self::Paladin => 'Паладин',
            self::Ranger => 'Слідопит',
            self::Sorcerer => 'Чародій',
            self::Warlock => 'Чаклун',
            self::Wizard => 'Чарівник',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }
}
