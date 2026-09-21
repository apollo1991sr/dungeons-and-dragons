<?php

namespace Database\Seeders;

use App\Enums\ItemCategory;
use App\Enums\ItemClass;
use App\Enums\ItemRarity;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::updateOrCreate(
            [
                'slug' => 'alchemists-fire',
            ],
            [
                'name' => "Алхімічне полум'я",

                'image' => 'alchemists-fire.webp',

                'category' => ItemCategory::Consumables,
                'item_class' => ItemClass::Grenades,
                'type' => null,

                'proficiency' => null,

                'rarity' => ItemRarity::Common,

                'price' => 13,
                'weight' => 0.3,

                'stats' => null,

                'attributes' => [
                    [
                        'placement' => 'after-other',
                        'layout' => 'inline-row',
                        'html' => '
            <span class="icon action"></span>&nbsp;<span class="link">Дія</span>
            <span class="icon single-use"></span>&nbsp;<span class="accent">Одноразове</span>
        ',
                    ],
                ],

                'content' => [
                    [
                        'title' => 'Опис',
                        'type' => 'description',
                        'html' => '
                            За склом палають вихрі рідких іскор, що разом кружляють та зливаються.
                        ',
                    ],

                    [
                        'title' => 'Застосування',
                        'type' => 'html',
                        'html' => <<<'HTML'
                            <p>
                                Використувується як метальний снаряд.
                            </p>

                            <div class="table-block">
                                <span class="link">
                                    Алхімічне полум'я
                                </span>

                                <ul>
                                    <li>
                                        Жбурнути фіал рідкого вогню, що вибухає в місці влучання.
                                    </li>

                                    <li>
                                        Створює покрив з
                                        <span class="link">Вогню</span>
                                        у місці влучання.

                                        <br>

                                        <span class="accent">
                                            <span class="icon distance"></span>
                                            Дальність
                                        </span>: 60 ф
                                    </li>
                                </ul>
                            </div>

                            <div class="table-block">

                                <span class="link">
                                    Вогонь
                                </span>
                                [поверхня]

                                <ul>
                                    <li>
                                        Перебування на покриві спричиняє стан

                                        <span class="link nowrap">
                                            <span class="icon burning"></span>
                                            Горіння
                                        </span>

                                        <br>

                                        <span class="icon duration"></span>
                                        <span class="accent">
                                            Тривалість
                                        </span>: 3 ходи

                                        <br>

                                        <span class="accent">
                                            <span class="icon radius"></span>
                                            Радіус
                                        </span>: 10 ф
                                    </li>
                                </ul>

                                <div class="table-block-inner">

                                    <span class="link nowrap">
                                        <span class="icon burning"></span>
                                        Горіння
                                    </span>
                                    [стан]

                                    <ul>
                                        <li>
                                            Зазнає
                                            <span class="fire">
                                                1к4
                                                <span class="icon damage"></span>
                                                вогняної
                                            </span>
                                            шкоди кожного ходу.
                                        </li>

                                        <li>
                                            Усувається

                                            <span class="link">
                                                <span class="icon help squared glow"></span>
                                                Допомогою
                                            </span>,

                                            використанням
                                            <span class="link">
                                                Цілющого зілля
                                            </span>

                                            або станом

                                            <span class="link">
                                                <span class="icon wet"></span>
                                                Намоклість
                                            </span>.
                                        </li>

                                        <li>
                                            Має

                                            <span class="accent">
                                                <span class="icon immunity"></span>
                                                імунітет
                                            </span>

                                            якщо у стані

                                            <span class="link">
                                                <span class="icon wet"></span>
                                                Намоклості
                                            </span>.
                                        </li>

                                        <li>
                                            Можна використати

                                            <span class="link">
                                                <span class="icon dip squared glow"></span>
                                                Занурення
                                            </span>

                                            на персонажах чи об'єктах, що горять.
                                        </li>
                                    </ul>

                                </div>

                            </div>
                            HTML,
                    ],
                ],
            ]
        );
    }
}
