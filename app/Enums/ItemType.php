<?php

namespace App\Enums;

enum ItemType: string
{
    // Аксесуари
    case Amulets = 'amulets';
    case Rings = 'rings';
    case Cloaks = 'cloaks';

    // Військова зброя
    case Shortswords = 'shortswords';
    case Scimitars = 'scimitars';
    case WarPicks = 'war-picks';
    case Morningstars = 'morningstars';
    case Rapiers = 'rapiers';
    case Flails = 'flails';
    case Warhammers = 'warhammers';
    case Battleaxes = 'battleaxes';
    case Longswords = 'longswords';
    case Tridents = 'tridents';
    case Halberds = 'halberds';
    case Greatswords = 'greatswords';
    case Greataxes = 'greataxes';
    case Glaives = 'glaives';
    case Mauls = 'mauls';
    case Pikes = 'pikes';
    case Longbows = 'longbows';
    case HandCrossbows = 'hand-crossbows';
    case HeavyCrossbows = 'heavy-crossbows';

    // Проста зброя
    case Daggers = 'daggers';
    case Clubs = 'clubs';
    case LightHammers = 'light-hammers';
    case Sickles = 'sickles';
    case Handaxes = 'handaxes';
    case Maces = 'maces';
    case Javelins = 'javelins';
    case Quarterstaves = 'quarterstaves';
    case Spears = 'spears';
    case Greatclubs = 'greatclubs';
    case LightCrossbows = 'light-crossbows';
    case Shortbows = 'shortbows';

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

            // Зброя військова
            self::Shortswords => 'Короткі мечі',
            self::Scimitars => 'Шаблі',
            self::WarPicks => 'Келепи',
            self::Morningstars => 'Морґенштерни',
            self::Rapiers => 'Рапіри',
            self::Flails => 'Ціпи',
            self::Warhammers => 'Бойові молоти',
            self::Battleaxes => 'Бойові сокири',
            self::Longswords => 'Довгі мечі',
            self::Tridents => 'Тризуби',
            self::Halberds => 'Алебарди',
            self::Greatswords => 'Великі мечі',
            self::Greataxes => 'Великі сокири',
            self::Glaives => 'Глефи',
            self::Mauls => 'Молоти',
            self::Pikes => 'Піки',
            self::Longbows => 'Довгі луки',
            self::HandCrossbows => 'Малі арбалети',
            self::HeavyCrossbows => 'Важкі арбалети',

            // Зброя проста
            self::Daggers => 'Кинджали',
            self::Clubs => 'Довбні',
            self::LightHammers => 'Легкі молоти',
            self::Sickles => 'Серпи',
            self::Handaxes => 'Сокири',
            self::Maces => 'Булави',
            self::Javelins => 'Сулиці',
            self::Quarterstaves => 'Палиці',
            self::Spears => 'Списи',
            self::Greatclubs => 'Великі довбні',
            self::LightCrossbows => 'Легкі арбалети',
            self::Shortbows => 'Короткі луки',

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
