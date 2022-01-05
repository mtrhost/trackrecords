<?php

namespace App\Models\Setting;

use App\Models\Faction\Faction;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;

class SettingRole extends Model
{
    protected $fillable = [
        'setting_id', 'role_id', 'faction_id'
    ];

    protected $casts = [
        'setting_id' => 'integer',
        'role_id' => 'integer',
        'faction_id' => 'integer',
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function faction()
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
