<?php

namespace App\Models\Faction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * FactionGroup
 * 
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Collection|null $factions
 *
 * @author jcshow
 * @package App\Models\Faction
 */
class FactionGroup extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'title', 'alias'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'title' => 'string',
        'alias' => 'string',
    ];

    /**
     * Factions
     * 
     * @return HasMany
     */
    public function factions(): HasMany
    {
        return $this->hasMany(Faction::class, 'group_id', 'id');
    }
}
