<?php

namespace App\Models\Faction;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    protected $fillable = [
        'name', 'color', 'group_id', 'alias', 'sort'
    ];

    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
        'color' => 'string',
        'group_id' => 'integer',
        'sort' => 'integer'
    ];

    public function group()
    {
        return $this->belongsTo(FactionGroup::class, 'group_id', 'id');
    }
}
