<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name', 'alias', 'condition', 'description', 'image_original', 'sort'
    ];

    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
        'condition' => 'string',
        'description' => 'string',
        'image_original' => 'string',
        'sort' => 'integer',
    ];

    public function getImageOriginalAttribute($value)
    {
        return '/static/images/' . $value;
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_achievements', 'achievement_id', 'player_id');
    }
}
