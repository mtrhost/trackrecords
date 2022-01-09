<?php

namespace App\Models\Setting;

use App\Models\Faction\Faction;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SettingRole
 * 
 * @property int $id
 * @property int $setting_id
 * @property int $role_id
 * @property int $faction_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Setting $setting
 * @property-read Role $role
 * @property-read Faction $faction
 *
 * @author jcshow
 * @package App\Models\Setting
 */
class SettingRole extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'setting_id', 'role_id', 'faction_id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'setting_id' => 'integer',
        'role_id' => 'integer',
        'faction_id' => 'integer',
    ];

    /**
     * Setting
     * 
     * @return BelongsTo
     */
    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'setting_id', 'id');
    }

    /**
     * Role
     * 
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Faction
     * 
     * @return BelongsTo
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class, 'faction_id', 'id');
    }

    public static function convertSyncedData($data)
    {
        $result = [];
        foreach($data as $dat) {
            $result[$dat['role_id']] = ['faction_id' => $dat['faction_id']];
        }
        
        return $result;
    }
}
