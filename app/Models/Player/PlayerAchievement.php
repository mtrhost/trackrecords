<?php

namespace App\Models\Player;

use App\Models\Achievement\Achievement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PlayerAchievement
 * 
 * @property int $id
 * @property int $player_id
 * @property int $achievement_id
 * 
 * @property-read Player $player
 * @property-read Achievement $achievement
 *
 * @author jcshow
 * @package App\Models\Player
 */
class PlayerAchievement extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'player_id', 'achievement_id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'player_id' => 'integer',
        'achievement_id' => 'integer',
    ];

    /**
     * Player
     * 
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    /**
     * Achievement
     * 
     * @return BelongsTo
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class, 'achievement_id', 'id');
    }
}
