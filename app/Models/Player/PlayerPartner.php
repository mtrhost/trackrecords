<?php

namespace App\Models\Player;

use App\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerPartner extends Model
{
    protected $fillable = [
        'player_one_id', 'player_two_id', 'games_count', 'wins_count'
    ];

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
