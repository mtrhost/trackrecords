<?php

namespace App\Models\Player;

use App\Dictionaries\Player\PlayerStatusDictionary;
use App\Models\Achievement\Achievement;
use App\Models\Faction\FactionGroup;
use App\Models\Game\Game;
use App\Models\Game\GameRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Player
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $profile
 * @property string|null $last_game
 * @property Carbon|null $last_profile_parse_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Collection|null $game_roles
 * @property-read Collection|null $games_mastered
 * @property-read Game|null $last_game_mastered
 * @property-read PlayerStatistics $statistics
 * @property-read Collection|null $achievements
 * @property-read Collection|null $games
 * @property-read Collection|null $partners
 * @property-read string $profile_image
 *
 * @author jcshow
 * @package App\Models\Player
 */
class Player extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'profile', 'last_game', 'last_profile_parse_date', 'status'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'status' => 'integer'
    ];

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'last_profile_parse_date'
    ];

    const GAMES_COUNT_FOR_STATISTICS_APPEARANCE = 20;
    const MONTHS_TO_COUNT_AS_INACTIVE = 6;
    const WINRATE_COLORS = [
        'bad' => '#b00b13',
        'average' => '#4e90ec',
        'good' => '#D7B740',
        'inactive' => '#D3D3D3',
        'lowGames' => '#5F9EA0',
        'scammer' => 'rgb(70, 3, 9)'
    ];

    protected $appends = ['profileImage'];

    /**
     * Game roles
     * 
     * @return HasMany
     */
    public function gameRoles(): HasMany
    {
        return $this->hasMany(GameRole::class, 'player_id');
    }

    /**
     * Games mastered
     * 
     * @return HasMany
     */
    public function gamesMastered(): HasMany
    {
        return $this->hasMany(Game::class, 'master_id', 'id');
    }

    /**
     * Last game mastered
     * 
     * @return HasOne
     */
    public function lastGameMastered(): HasOne
    {
        return $this->hasOne(Game::class, 'master_id', 'id')->latest();
    }

    /**
     * Statistics
     * 
     * @return HasOne
     */
    public function statistics(): HasOne
    {
        return $this->hasOne(PlayerStatistics::class, 'player_id', 'id');
    }

    /**
     * Achievements
     * 
     * @return BelongsToMany
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'player_achievements', 'player_id', 'achievement_id')->orderBy('achievements.sort', 'asc');
    }

    /**
     * Games
     * 
     * @return BelongsToMany
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_roles', 'player_id', 'game_id');
    }

    /**
     * Partners
     * 
     * @return BelongsToMany
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'player_partners', 'player_one_id', 'player_two_id')
            ->withPivot('games_count', 'wins_count');
    }

    /**
     * @param Builder $q
     * 
     * @return Builder
     */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', PlayerStatusDictionary::ACTIVE);
    }

    /**
     * @return string
     */
    public function getProfileImageAttribute($value): string
    {
        if(\Storage::disk('public')->exists('players/' . $this->id . '/profileImage.png'))
            return \Storage::disk('public')->url('players/' . $this->id . '/profileImage.png');
        else
            return '/static/images/default_large.png';
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

    /**
     * Is player scammer
     * 
     * @return bool
     */
    public function isScamer(): bool
    {
        return $this->status === PlayerStatusDictionary::SCAMMER;
    }

    /**
     * Is player active
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        $lastGame = Game::select('id', 'date')->orderByDesc('number')->first();
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
        $this->winrate = $this->statistics->games_count === 0 ? 0 : round(
            ($this->statistics->wins_count / $this->statistics->games_count) * 100, $accuracy
        );
        $this->getWinrateColor();
        $this->getPartnersWinrate(15, $accuracy);

        return $this->winrate;
    }

    public function getWinrateColor()
    {
        if ($this->isScamer()) {
            $this->winrate_color = self::WINRATE_COLORS['scammer'];
        } else if(!$this->is_active) {
            $this->winrate_color = self::WINRATE_COLORS['inactive'];
        } else if ($this->statistics->games_count < 20) {
            $this->winrate_color = self::WINRATE_COLORS['lowGames'];
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

    public function getPartnersWinrate($gamesFilter = 10, $accuracy = 0)
    {
        $this->partners = $this->partners->filter(static function ($partner) use ($gamesFilter) {
            return $partner->pivot->games_count >= $gamesFilter;
        })->map(static function ($partner) use ($accuracy) {
            $partner->games_count = $partner->pivot->games_count;
            $partner->wins_count = $partner->pivot->wins_count;
            unset($partner->pivot);
            $partner->winrate = round(
                (IntVal($partner->wins_count) / IntVal($partner->games_count) * 100), $accuracy
            );

            return $partner;
        })->sortByDesc('winrate')->values();
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

    public function getActiveAverageDaysSurvived($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getActiveAverageDaysSurvived($accuracy);
    }

    public function getMafiaAverageDaysSurvived($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getMafiaAverageDaysSurvived($accuracy);
    }

    public function getNeutralAverageDaysSurvived($accuracy = 0)
    {
        if(empty($this->statistics))
            return 0;

        return $this->statistics->getNeutralAverageDaysSurvived($accuracy);
    }
}
