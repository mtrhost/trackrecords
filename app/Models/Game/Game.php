<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class Game extends Model
{
    protected $fillable = [
        'setting_id', 'master_id', 'name', 'number', 'length', 'date', 'link', 'status', 'players_count', 'active_count',
        'mafia_count', 'neutral_count'
    ];

    protected $casts = [
        'setting_id' => 'integer',
        'master_id' => 'integer',
        'name' => 'string',
        'number' => 'integer',
        'length' => 'integer',
        'link' => 'string',
        'status' => 'string',
        'players_count' => 'integer',
        'active_count' => 'integer',
        'mafia_count' => 'integer',
        'neutral_count' => 'integer',
    ];

    public function winners()
    {
        return $this->hasMany(GameWinner::class, 'game_id');
    }
    public function master()
    {
        return $this->belongsTo(Player::class, 'master_id', 'id');
    }
    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_id', 'id');
    }
    public function roles()
    {
        return $this->hasMany(GameRole::class, 'game_id', 'id');
    }
    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_roles', 'game_id', 'player_id');
    }

    public function getWinnersString()
    {
        $temp = [];
        if(!empty($this->status)) {
            $temp[] = '<span class="failed-game">' . $this->status . '</span>';
        } else {
            foreach($this->winners as $winner) {
                if($winner->faction->group->alias === 'no-role')
                    continue;
    
                $temp[] = '<span class="faction-group-' . $winner->faction->group->alias . '">' . $winner->faction->group->title_for_games . '</span>';
            }
        }

        return $this->winnersString = implode(',', $temp);
    }

    public function getRoleString()
    {
        if($this->roles->isEmpty() || !empty($this->status))
            return false;

        return $this->roleString = '<span class="faction-group-' . $this->roles->first()->faction->group->alias . '">' . $this->roles->first()->role->name . '</span>';
    }

    public function getStatusString()
    {
        if($this->roles->isEmpty())
            return false;

        if(!empty($this->status)) {
            return $this->statusString = '<span class="failed-game">' . $this->status . '</span>';
        }
        
        $statusString = '';
        $role = $this->roles->first();
        if(in_array($role->faction->id, $this->winners->pluck('faction.id')->toArray()) && $role->status->alias !== 'lightning') {
            $statusString .= '<span class="faction-group-active">';
            if($role->status->alias === 'survivor') {
                $statusString .= $role->status->name;
            } else {
                $statusString .= $role->status->name . ' ' . $role->day . ' ' . $role->timeStatus->name;
            }
            $statusString .= ', Победа';
        } else {
            $statusString .= '<span class="faction-group-mafia">';
            if($role->status->alias === 'survivor') {
                $statusString .= $role->status->name;
            } else {
                $statusString .= $role->status->name . ' ' . $role->day . ' ' . $role->timeStatus->name;
            }
            $statusString .= ', Поражение';
        }

        $statusString .= '</span>';

        return $this->statusString = $statusString;
    }

    public function transformToDate($string)
    {
        $months = ['Дек' => 'Dec', 'Янв' => 'Jan', 'Фев' => 'Feb', 'Мар' => 'Mar', 'Апр' => 'Apr', 'Май' => 'May', 
            'Июн' => 'Jun', 'Июл' => 'Jul', 'Авг' => 'Aug', 'Сен' => 'Sep', 'Окт' => 'Oct', 'Ноя' => 'Nov'];
        foreach($months as $dickhead => $trueguy) {
            if(strpos($string, $dickhead)) {
                $string = str_replace($dickhead, $trueguy, $string);
            }
        }
        $date = Carbon::createFromFormat('d M Y H:i', strtolower(iconv('utf-8', 'cp1251', $string)), 'Asia/Novosibirsk');
        $date->setTimezone('Europe/Moscow');

        return $date;
    }

    public function saveWithRoles($data)
    {
        $roles = $data['roles'];
        unset($data['roles']);
        $winners = $data['winners'];
        unset($data['winners']);
        //$data['date'] = $this->transformToDate($data['date'])->toDateTimeString();
        $this->fill($data);
        if(!$this->save()) {
            return false;
        }

        if(!empty($roles[0]['player_id'])) {
            $this->roles()->delete();
            foreach($roles as $role) {
                if (!empty($role['player_id'])) {
                    if(!GameRole::create(array_merge($role, ['game_id' => $this->id]))) {
                        return false;
                    }
                }
            }
        }
        
        if(!empty($winners)) {
            foreach($winners as $winner) {
                if(!GameWinner::create(['game_id' => $this->id, 'faction_id' => $winner])) {
                    return false;
                }
            }
        }

        Artisan::call('statistics:fill');
        Artisan::call('achievements:assign');

        return true;
    }

}
