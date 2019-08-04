<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameStatus extends Model
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
