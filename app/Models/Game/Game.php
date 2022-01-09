<?php

namespace App\Models\Game;

use App\Dictionaries\Game\GameStatusDictionary;
use App\Models\Faction\FactionGroup;
use App\Models\Player\Player;
use App\Models\Setting\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Artisan;

/**
 * Game
 * 
 * @property int $id
 * @property int $setting_id
 * @property int $master_id
 * @property string $name
 * @property int $number
 * @property int $length
 * @property Carbon|null $date
 * @property string $link
 * @property int $status
 * @property int $players_count
 * @property int $active_count
 * @property int $mafia_count
 * @property int $neutral_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Collection|null $winners
 * @property-read Player $master
 * @property-read Setting $setting
 * @property-read Collection|null $roles
 * @property-read Collection|null $players
 *
 * @author jcshow
 * @package App\Models\Game
 */
class Game extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'setting_id', 'master_id', 'name', 'number', 'length', 'date', 'link', 'status', 'players_count', 'active_count',
        'mafia_count', 'neutral_count'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'setting_id' => 'integer',
        'master_id' => 'integer',
        'number' => 'integer',
        'length' => 'integer',
        'players_count' => 'integer',
        'active_count' => 'integer',
        'mafia_count' => 'integer',
        'neutral_count' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Game winners
     * 
     * @return HasMany
     */
    public function winners(): HasMany
    {
        return $this->hasMany(GameWinner::class, 'game_id');
    }

    /**
     * Game master
     * 
     * @return BelongsTo
     */
    public function master(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'master_id', 'id');
    }

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
     * Roles
     * 
     * @return HasMany
     */
    public function roles(): HasMany
    {
        return $this->hasMany(GameRole::class, 'game_id', 'id');
    }

    /**
     * Players
     * 
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_roles', 'game_id', 'player_id');
    }

    /**
     * Flag marks game as non completed
     * 
     * @return bool
     */
    public function notCompleted(): bool
    {
        return $this->status !== GameStatusDictionary::COMPLETED;
    }

    public function getWinnersString()
    {
        $temp = [];
        if($this->notCompleted()) {
            $temp[] = '<span class="failed-game">' . GameStatusDictionary::getValueByKey($this->status) . '</span>';
        } else {
            foreach($this->winners as $winner) {
                if($winner->faction->group->alias === 'no-role')
                    continue;
    
                $temp[] = '<span class="faction-group-' . $winner->faction->group->alias . '">' . $winner->faction->group->title_for_games . '</span>';
            }
        }

        return $this->winnersString = implode(',', $temp);
    }

    public function setWinnerFields()
    {
        $this->isCityWin = in_array(FactionGroup::where('alias', 'no-role')->first()->id, $this->winners->pluck('faction.group.id')->toArray());
        $this->isMafiaWin = in_array(FactionGroup::where('alias', 'mafia')->first()->id, $this->winners->pluck('faction.group.id')->toArray());
        $this->isNeutralWin = in_array(FactionGroup::where('alias', 'neutral')->first()->id, $this->winners->pluck('faction.group.id')->toArray());
        $this->isFailed = $this->notCompleted();
    }

    public function getRoleString()
    {
        if($this->roles->isEmpty() || $this->notCompleted())
            return false;

        return $this->roleString = '<span class="faction-group-' . $this->roles->first()->faction->group->alias . '">' . $this->roles->first()->role->name . '</span>';
    }

    public function getStatusString()
    {
        if($this->roles->isEmpty())
            return false;

        if($this->notCompleted()) {
            return $this->statusString = '<span class="failed-game">' . GameStatusDictionary::getValueByKey($this->status) . '</span>';
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
