<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FactionGroup extends Model
{
    protected $fillable = [
        'title', 'alias'
    ];

    protected $casts = [
        'title' => 'string',
        'alias' => 'string',
    ];

    public function factions()
    {
        return $this->hasMany(Faction::class, 'group_id', 'id');
    }
}
