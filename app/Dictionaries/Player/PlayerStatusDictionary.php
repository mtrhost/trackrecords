<?php

namespace App\Dictionaries\Player;

use App\Dictionaries\BaseDictionary;

/**
 * Player status dictionary
 *
 * @author jcshow
 * 
 * @package App\Dictionaries\Player
 */
class PlayerStatusDictionary extends BaseDictionary
{
    public const ACTIVE = 1;

    public const SCAMMER = 2;

    /**
     * {@inheritDoc}
     */
    public static function getValues(): array
    {
        return [
            static::ACTIVE => 'Игрок',
            static::SCAMMER => 'Скамер',
        ];
    }
}
