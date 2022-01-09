<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

/**
 * GameStatus
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
class GameStatus extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'alias'
    ];
}
