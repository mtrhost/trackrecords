<?php

namespace App\Models\Game;

use App\Models\Faction\Faction;
use App\Models\Player\Player;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GameRole
 * 
 * @property int $id
 * @property int $game_id
 * @property int $player_id
 * @property int $role_id
 * @property int $faction_id
 * @property int $status_id
 * @property int $day
 * @property int $time_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Game $game
 * @property-read Player $player
 * @property-read Role $role
 * @property-read Faction $faction
 * @property-read GameStatus $status
 * @property-read GameTimeStatus $time_status
 *
 * @author jcshow
 * @package App\Models\Game
 */
class GameRole extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'game_id', 'player_id', 'role_id', 'faction_id', 'status_id', 'day', 'time_status_id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'game_id' => 'integer',
        'player_id' => 'integer',
        'role_id' => 'integer',
        'faction_id' => 'integer',
        'status_id' => 'integer',
        'day' => 'integer',
        'time_status_id' => 'integer',
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
     * Player
     * 
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Role
     * 
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
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

    /**
     * Status
     * 
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(GameStatus::class, 'status_id', 'id');
    }

    /**
     * Time status
     * 
     * @return BelongsTo
     */
    public function timeStatus(): BelongsTo
    {
        return $this->belongsTo(GameTimeStatus::class, 'time_status_id', 'id');
    }
}
