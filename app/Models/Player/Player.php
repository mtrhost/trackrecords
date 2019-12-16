<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

use App\Traits\PDParser;

class Player extends Model
{
    use PDParser;

    protected $fillable = [
        'name', 'profile', 'last_game', 'last_profile_parse_date'
    ];

    protected $casts = [
        'name' => 'string',
        'profile' => 'string'
    ];

    protected $dates = [
        'last_profile_parse_date'
    ];

    const GAMES_COUNT_FOR_STATISTICS_APPEARANCE = 20;
    const MONTHS_TO_COUNT_AS_INACTIVE = 3;
    const WINRATE_COLORS = [
        'bad' => '#b00b13',
        'average' => '#4e90ec',
        'good' => '#D7B740',
        'inactive' => '#D3D3D3'
    ];

    protected $appends = ['profileImage'];

    public function gameRoles()
    {
        return $this->hasMany(GameRole::class, 'player_id');
    }
    public function gamesMastered()
    {
        return $this->hasMany(Game::class, 'master_id', 'id');
    }
    public function lastGameMastered()
    {
        return $this->hasOne(Game::class, 'master_id', 'id')->latest();
    }
    public function statistics()
    {
        return $this->hasOne(PlayerStatistics::class, 'player_id', 'id');
    }
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'player_achievements', 'player_id', 'achievement_id')->orderBy('achievements.sort', 'asc');
    }
    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_roles', 'player_id', 'game_id');
    }

    public function getProfileImageAttribute($value)
    {
        if(\Storage::disk('public')->exists('players/' . $this->id . '/profileImage.png'))
            return \Storage::disk('public')->url('players/' . $this->id . '/profileImage.png');
        else
            return '/static/images/default_large.png';
    }

    public function parseProfile()
    {
        $profileData = PDParser::parseProfileData($this->profile);
        return $this->last_active = $profileData['lastActive'];
    }

    public function updatePlayerAvatar()
    {
        if(is_null($this->last_profile_parse_date) || Carbon::now()->subDays(3)->gt($this->last_profile_parse_date)) {
            $avatar = PDParser::parseProfileAvatar($this->profile);
            if($avatar) {
                $image = Image::make($avatar);
                //$format = preg_replace('/(.*\.)(.*)(\?.*)/', '$2', $avatar);
                $publicPath = \Storage::disk('public');
                $folderPath = 'players/' . $this->id . '/';
                if (!file_exists($publicPath->path($folderPath))) {
                    mkdir($publicPath->path($folderPath), 0676, true);
                }
                $imageName = 'profileImage.png';
                $absolutePath = $publicPath->path($folderPath . $imageName);
                $result = $image->save($absolutePath, 100);
                if($result) {
                    $this->last_profile_parse_date = Carbon::now()->toDateTimeString();
                    $this->save();
                }
            }
        }
    }

    public function countLastGameDate($gameRole)
    {
        if(is_null($this->last_game) || Carbon::parse($this->last_game)->lt(Carbon::parse($gameRole->game->date)))
            $this->last_game = $gameRole->game->date;
    }
    public function countLastGameMastered($lastGameMastered)
    {
        if(is_null($this->last_game) || Carbon::parse($this->last_game)->lt(Carbon::parse($lastGameMastered->date)))
            $this->last_game = $lastGameMastered->date;
    }

    public function isActive()
    {
        $lastGame = Game::select('id', 'date')->latest()->first();
        if(is_null($this->last_game) || Carbon::parse($lastGame->date)->subMonths(self::MONTHS_TO_COUNT_AS_INACTIVE)->gt($this->last_game))
            return false;

        return true;
    }

    public function getGamesCount($mastered = false)
    {
        if(empty($this->statistics))
            return 0;

        $gamesCount = $this->statistics->games_count;
        if($mastered)
            $gamesCount += $this->gamesMastered()->count();

        return $gamesCount;
    }
    public function getLightningsCount()
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getLightningsCount();
    }

    public function getWinrate($accuracy = 0)
    {
        if(!isset($this->statistics)) {
            $this->low_games_count = true;
            return $this->winrate = 0;
        }
            
        if($this->statistics->games_count < self::GAMES_COUNT_FOR_STATISTICS_APPEARANCE)
            $this->low_games_count = true;

        $this->is_active = $this->isActive();


        $this->getFactionsWinrateStatistics(FactionGroup::get()->toArray(), $accuracy);
        $this->winrate = round(
            ($this->statistics->wins_count / $this->statistics->games_count) * 100, $accuracy
        );
        $this->getWinrateColor();

        return $this->winrate;
    }

    public function getWinrateColor()
    {
        if(!$this->is_active) {
            $this->winrate_color = self::WINRATE_COLORS['inactive'];
        } else {
            if($this->winrate < 35) {
                $this->winrate_color = self::WINRATE_COLORS['bad'];
            } else if ($this->winrate > 60) {
                $this->winrate_color = self::WINRATE_COLORS['good'];
            } else {
                $this->winrate_color = self::WINRATE_COLORS['average'];
            }
        }
    }

    public function getFactionsWinrateStatistics($factionGroups, $accuracy = 0)
    {
        if(empty($factionGroups))
            return false;

        foreach($factionGroups as $group) {
            $this->getFactionWinrate($group['alias'], $accuracy);
        }
    }

    public function getFactionWinrate($alias, $accuracy = 0)
    {
        $alias = trim(preg_replace('<\W+>', "_", $alias), "_");
        if(!isset($this->statistics) || IntVal($this->statistics->{"games_count_$alias"}) === 0)
            return $this->{"winrate_$alias"} = 0;

        return $this->{"winrate_$alias"} = round(
            (IntVal($this->statistics->{"wins_$alias"}) / IntVal($this->statistics->{"games_count_$alias"}) * 100), $accuracy
        );
    }

    public function getCivilianWinrate($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getCivilianWinrate($accuracy);
    }

    public function getRoleRate($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;
            
        return $this->statistics->getRoleRate($accuracy);
    }

    public function getCityNegativeActionsRate($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;
            
        return $this->statistics->getCityNegativeActionsRate($accuracy);
    }

    public function getMafiaAverageDaysSurvived($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getMafiaAverageDaysSurvived($accuracy);
    }

    public function assignRegularAchievements($achievements)
    {
        if($this->games_mastered_count > 9) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'master'; })->first()->id))
                return false;
        }

        if(empty($this->statistics))
            return true;

        if($this->statistics->wins_neutral > 0) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'ghost'; })->first()->id))
                return false;
        }
        if($this->statistics->wins_mafia > 4) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'leery'; })->first()->id))
                return false;
        }
        if($this->statistics->wins_active > 9) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'sheriff'; })->first()->id))
                return false;
        }
        if(($this->statistics->games_count + $this->games_mastered_count) > 99) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'oldschool'; })->first()->id))
                return false;
        }
        if($this->statistics->games_count_mafia > 10 && $this->getFactionWinrate('mafia') > 60) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'bewatcher'; })->first()->id))
                return false;
        }
        if($this->statistics->games_count_active > 15 && $this->getFactionWinrate('active') > 65) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'cityLegend'; })->first()->id))
                return false;
        }
        return true;
    }

    public function assignStaticAchievements($achievements)
    {
        if($this->id === 60) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['civilian2011', 'neutral2011', 'active2012', 'active2013']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 6) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['active2011']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 46) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['mafia2011']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 8) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2011']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 11) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['civilian2012', 'civilian2016']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 13) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['mafia2012', 'civilian2013', 'mafia2013', 'civilian2014', 'active2015']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 10) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2012', 'mafia2014']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 100) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['civilian2013']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 102) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2013', 'neutral2014', 'active2017']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 21) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['civilian2014', 'civilian2015']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 95) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['active2014']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 207) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['mafia2015', 'mafia2016']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 218) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2015', 'mafia2017']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 111) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['active2016']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 85) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['active2016']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 228) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2016']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 183) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['civilian2017']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 269) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['neutral2017']); })->pluck('id')->toArray())
            )
                return false;
        }
        if($this->id === 212) {
            if(!$this->achievements()->syncWithoutDetaching($achievements->filter(function($value){ 
                return in_array($value->alias, ['mindgames']); })->pluck('id')->toArray())
            )
                return false;
        }
        return true;
    }
}
