<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name', 'players_count', 'author_id'
    ];

    protected $casts = [
        'name' => 'string',
        'author_id' => 'integer',
        'players_count' => 'integer'
    ];

    public function author()
    {
        return $this->belongsTo(Player::class, 'author_id', 'id');
    }
    public function games()
    {
        return $this->hasMany(Game::class, 'setting_id', 'id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'setting_roles', 'setting_id', 'role_id');
    }
    public function settingRoles()
    {
        return $this->hasMany(SettingRole::class, 'setting_id', 'id');
    }

    public static function saveWithRoles($request)
    {
        $roles = $request['roles'];
        unset($request['roles']);
        $setting = new Setting($request);
        if(!$setting->save()) {
            return false;
        }
        
        $roles = SettingRole::convertSyncedData($roles);
        if(!$setting->roles()->sync($roles)) {
            return false;
        }

        return true;
    }
}
