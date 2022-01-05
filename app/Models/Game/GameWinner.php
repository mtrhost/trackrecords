<?php

namespace App\Models\Game;

use App\Models\Faction\Faction;
use Illuminate\Database\Eloquent\Model;

class GameWinner extends Model
{
    protected $fillable = [
        'game_id', 'faction_id'
    ];

    protected $casts = [
        'game_id' => 'integer',
        'faction_id' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function faction()
    {
        return $this->belongsTo(Faction::class, 'faction_id');
    }
}
