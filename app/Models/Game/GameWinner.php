<?php

namespace App\Models\Game;

use App\Models\Faction\Faction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GameWinner
 * 
 * @property int $id
 * @property int $game_id
 * @property int $faction_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Game $game
 * @property-read Faction $faction
 *
 * @author jcshow
 * @package App\Models\Game
 */
class GameWinner extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'game_id', 'faction_id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'game_id' => 'integer',
        'faction_id' => 'integer',
    ];

    /**
     * Game
     * 
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Faction
     * 
     * @return BelongsTo
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class, 'faction_id');
    }
}
