<?php

namespace App\Enums;

enum ItemType: string
{
    // Аксесуари
    case Amulets = 'amulets';
    case Rings = 'rings';
    case Cloaks = 'cloaks';

    // Зброя
    case Longsword = 'longsword';
    case Greatswords = 'greatswords';
    case Longbows = 'longbows';
    case Shortbows = 'shortbows';
    case WarPicks = 'war-picks';
    case Daggers = 'daggers';
    case Mauls = 'mauls';
    case Rapiers = 'rapiers';

    // Обладунки
    case HeavyArmour = 'heavy-armour';
    case MediumArmour = 'medium-armour';
    case LightArmour = 'light-armour';
    case Helmets = 'helmets';
    case Shield = 'shield';

    // Одяг
    case Headwear = 'headwear';
    case Belts = 'belts';
    case Gloves = 'gloves';
    case Boots = 'boots';

    public function label(): string
    {
        return match ($this) {
            // Аксесуари
            self::Amulets => 'Амулети',
            self::Rings => 'Персні',
            self::Cloaks => 'Плащі',

            // Зброя
            self::Longsword => 'Довгі мечі',
            self::Greatswords => 'Великі мечі',
            self::Longbows => 'Довгі луки',
            self::Shortbows => 'Короткі луки',
            self::WarPicks => 'Келепи',
            self::Daggers => 'Кинджали',
            self::Mauls => 'Молоти',
            self::Rapiers => 'Рапіри',

            // Обладунки
            self::HeavyArmour => 'Важкі обладунки',
            self::MediumArmour => 'Середні обладунки',
            self::LightArmour => 'Легкі обладунки',
            self::Helmets => 'Шоломи',
            self::Shield => 'Щити',

            // Одяг
            self::Headwear => 'Головні убори',
            self::Belts => 'Пояси',
            self::Gloves => 'Рукавиці',
            self::Boots => 'Чоботи',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            // Головні убори використовують ту саму іконку, що й шоломи
            self::Headwear => 'helmets',

            default => $this->value,
        };
    }
}
