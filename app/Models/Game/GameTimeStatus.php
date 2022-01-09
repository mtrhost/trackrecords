<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

/**
 * GameTimeStatus
 * 
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @author jcshow
 * @package App\Models\Game
 */
class GameTimeStatus extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'alias'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
    ];
}
