<?php

namespace App\Models\Player;

use App\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PlayerPartner
 * 
 * @property int $id
 * @property int $player_one_id
 * @property int $player_two_id
 * @property int $games_count
 * @property int $wins_count
 * 
 * @property-read Player $player_one
 * @property-read Player $player_two
 *
 * @author jcshow
 * @package App\Models\Player
 */
class PlayerPartner extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'player_one_id', 'player_two_id', 'games_count', 'wins_count'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'player_one_id' => 'integer',
        'player_two_id' => 'integer',
        'games_count' => 'integer',
        'wins_count' => 'integer'
    ];

    /**
     * @return BelongsTo
     */
    public function playerOne(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_one_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function playerTwo(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_two_id', 'id');
    }
}
