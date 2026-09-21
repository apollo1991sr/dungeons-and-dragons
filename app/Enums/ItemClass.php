<?php

namespace App\Enums;

enum ItemClass: string
{
    case SimpleWeapon = 'simple-weapon';
    case MartialWeapon = 'martial-weapon';

    case Armour = 'armour';
    case Clothing = 'clothing';
    case Accessories = 'accessories';

    case Arrows = 'arrows';
    case Elixirs = 'elixirs';
    case Potions = 'potions';
    case Scrolls = 'scrolls';
    case Grenades = 'grenades';
    case Coatings = 'coatings';
    case Traps = 'traps';

    public function label(): string
    {
        return match ($this) {
            self::SimpleWeapon => 'Проста зброя',
            self::MartialWeapon => 'Бойова зброя',

            self::Armour => 'Обладунки',
            self::Clothing => 'Одяг',
            self::Accessories => 'Аксесуари',

            self::Arrows => 'Стріли',
            self::Elixirs => 'Еліксири',
            self::Potions => 'Зілля',
            self::Scrolls => 'Сувої',
            self::Grenades => 'Гранати',
            self::Coatings => 'Покриття',
            self::Traps => 'Пастки',
        };
    }

    public function icon(): string
    {
        return $this->value;
    }
}
