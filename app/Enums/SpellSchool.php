<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SpellSchool: string implements HasLabel
{
    case Abjuration = 'abjuration';
    case Conjuration = 'conjuration';
    case Divination = 'divination';
    case Enchantment = 'enchantment';
    case Evocation = 'evocation';
    case Illusion = 'illusion';
    case Necromancy = 'necromancy';
    case Transmutation = 'transmutation';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Abjuration => 'Віднадження',
            self::Conjuration => 'Прикликання',
            self::Divination => 'Ворожба',
            self::Enchantment => 'Зачарування',
            self::Evocation => 'Втілення',
            self::Illusion => 'Ілюзія',
            self::Necromancy => 'Некромантія',
            self::Transmutation => 'Перетворення',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }

    public function cssClass(): string
    {
        return 'school-' . $this->value;
    }
}
