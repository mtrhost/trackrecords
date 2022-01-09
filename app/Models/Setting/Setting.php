<?php

namespace App\Models\Setting;

use App\Models\Game\Game;
use App\Models\Player\Player;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Setting
 * 
 * @property int $id
 * @property string $name
 * @property int $players_count
 * @property int $author_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Player $author
 * @property-read Collection|null $games
 * @property-read Collection|null $roles
 * @property-read Collection|null $setting_roles
 *
 * @author jcshow
 * @package App\Models\Setting
 */
class Setting extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'players_count', 'author_id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'author_id' => 'integer',
        'players_count' => 'integer'
    ];

    /**
     * Setting author
     * 
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'author_id', 'id');
    }

    /**
     * Games
     * 
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'setting_id', 'id');
    }

    /**
     * Roles
     * 
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'setting_roles', 'setting_id', 'role_id');
    }

    /**
     * Setting roles
     * 
     * @return HasMany
     */
    public function settingRoles(): HasMany
    {
        return $this->hasMany(SettingRole::class, 'setting_id', 'id');
    }

    public static function saveWithRoles($request)
    {
        $roles = $request['roles'];
        if (empty($roles)) {
            return false;
        }
        unset($request['roles']);
        $setting = new Setting($request);
        if(!$setting->save()) {
            return false;
        }
        
        // $roles = SettingRole::convertSyncedData($roles);
        // if(!$setting->roles()->sync($roles)) {
        //     return false;
        // }
        $preservedIds = [];
        foreach($roles as &$role) {
            $model = SettingRole::firstOrNew(['setting_id' => $setting->id, 'role_id' => $role['role_id'], 'faction_id' => $role['faction_id']]);
            if(!$model->save()) {
                return false;
            }
            $preservedIds[] = $model->id;
        }
        $oldData = SettingRole::where('setting_id', $setting->id)->whereNotIn('id', $preservedIds)->get();
        foreach($oldData as $old) {
            $old->delete();
        }

        return true;
    }
}
