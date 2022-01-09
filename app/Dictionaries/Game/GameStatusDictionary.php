<?php

namespace App\Dictionaries\Game;

use App\Dictionaries\BaseDictionary;

/**
 * Game status dictionary
 *
 * @author jcshow
 * 
 * @package App\Dictionaries\Game
 */
class GameStatusDictionary extends BaseDictionary
{
    public const IN_PROGRESS = 1;

    public const COMPLETED = 2;

    public const FAILED = 3;

    public const SAW = 4;

    public const DRAW = 5;

    public const SETTING = 6;

    public const FASTMAFIA = 7;

    public const SCAMMED = 8;

    /**
     * {@inheritDoc}
     */
    public static function getValues(): array
    {
        return [
            static::IN_PROGRESS => 'В процессе',
            static::COMPLETED => 'Завершена',
            static::FAILED => 'Зафейлена',
            static::SAW => 'Пила',
            static::DRAW => 'Ничья',
            static::SETTING => 'Сеттос)',
            static::FASTMAFIA => 'Фастмафия',
            static::SCAMMED => 'Заскамлено',
        ];
    }
}
