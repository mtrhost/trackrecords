<?php

namespace App\Models\Faction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Faction
 * 
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $alias
 * @property int $group_id
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read FactionGroup $group
 *
 * @author jcshow
 * @package App\Models\Faction
 */
class Faction extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'color', 'group_id', 'alias', 'sort'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'group_id' => 'integer',
        'sort' => 'integer'
    ];

    /**
     * Faction group
     * 
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(FactionGroup::class, 'group_id', 'id');
    }
}
