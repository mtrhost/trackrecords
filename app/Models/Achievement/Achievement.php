<?php

namespace App\Models\Achievement;

use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Achievement
 * 
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $condition
 * @property string $description
 * @property string $image_original
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Collection|null $players
 *
 * @author jcshow
 * @package App\Models\Achievement
 */
class Achievement extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'alias', 'condition', 'description', 'image_original', 'sort'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'sort' => 'integer',
    ];

    /**
     * @return string
     */
    public function getImageOriginalAttribute($value): string
    {
        return '/static/images/' . $value;
    }

    /**
     * Players
     * 
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'player_achievements', 'achievement_id', 'player_id');
    }
}
