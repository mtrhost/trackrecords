<?php

namespace App\Models\Game;

use App\Models\Faction\Faction;
use App\Models\Player\Player;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;

class GameRole extends Model
{
    protected $fillable = [
        'game_id', 'player_id', 'role_id', 'faction_id', 'status_id', 'day', 'time_status_id'
    ];

    protected $casts = [
        'game_id' => 'integer',
        'player_id' => 'integer',
        'role_id' => 'integer',
        'faction_id' => 'integer',
        'status_id' => 'integer',
        'day' => 'integer',
        'time_status_id' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function faction()
    {
        return $this->belongsTo(Faction::class, 'faction_id');
    }

    public function status()
    {
        return $this->belongsTo(GameStatus::class, 'status_id', 'id');
    }

    public function timeStatus()
    {
        return $this->belongsTo(GameTimeStatus::class, 'time_status_id', 'id');
    }
}
