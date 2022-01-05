<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class GameTimeStatus extends Model
{
    protected $fillable = [
        'name', 'alias'
    ];

    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
    ];

    public function game()
    {
        $this->hasMany(Game::class, 'status_id', 'id');
    }
}
