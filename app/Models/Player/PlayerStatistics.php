<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Model;

class PlayerStatistics extends Model
{
    protected $fillable = [
        'player_id', 'games_count_no_role', 'wins_no_role', 'games_count_active', 'wins_active', 'games_count_mafia', 'wins_mafia',
        'games_count_neutral', 'wins_neutral', 'lightnings_civilian', 'lightnings_other', 'banished_civilian', 'maximal_winstreak',
        'current_winstreak', 'days_survived_no_role', 'days_survived_active', 'days_survived_mafia', 'days_survived_neutral',
    ];

    protected $casts = [
        'player_id' => 'integer',
        'games_count_no_role' => 'integer',
        'wins_no_role' => 'integer',
        'games_count_active' => 'integer',
        'wins_active' => 'integer',
        'games_count_mafia' => 'integer',
        'wins_mafia' => 'integer',
        'games_count_neutral' => 'integer',
        'wins_neutral' => 'integer',
        'lightnings_civilian' => 'integer',
        'lightnings_other' => 'integer',
        'banished_civilian' => 'integer',
        'maximal_winstreak' => 'integer',
        'current_winstreak' => 'integer',
        'days_survived_no_role' => 'integer',
        'days_survived_active' => 'integer',
        'days_survived_mafia' => 'integer',
        'days_survived_neutral' => 'integer',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function getWinsCountAttribute()
    {
        return $this->wins_count = $this->wins_no_role + $this->wins_active + $this->wins_mafia + $this->wins_neutral;
    }
    public function getGamesCountAttribute()
    {
        return $this->games_count = $this->games_count_no_role + $this->games_count_active + $this->games_count_mafia + $this->games_count_neutral;
    }
    public function getCivilianGamesCountAttribute()
    {
        return $this->civilian_games_count = $this->games_count_no_role + $this->games_count_active;
    }

    public function getLightningsCount()
    {
        return $this->lightnings_civilian + $this->lightnings_other;
    }
    public function getCivilianWinrate($accuracy = 0)
    {
        if($this->civilian_games_count === 0)
            return 0;

        return round(
            (((IntVal($this->{'wins_no_role'}) + IntVal($this->wins_active)) / IntVal($this->civilian_games_count)) * 100), $accuracy
        );
    }
    public function getRoleRate($accuracy = 0)
    { 
        if($this->games_count === 0)
            return 0;

        return round((($this->games_count_active + $this->games_count_mafia + $this->games_count_neutral) / $this->games_count) * 100, $accuracy);
    }
    public function getCityNegativeActionsRate($accuracy = 0)
    {
        if($this->civilian_games_count === 0)
            return 0;

        return round((($this->lightnings_civilian + $this->banished_civilian) / $this->civilian_games_count) * 100, $accuracy);
    }
    public function getActiveAverageDaysSurvived($accuracy = 0)
    {
        if($this->games_count_active === 0)
            return 0;

        return round(($this->days_survived_active / $this->games_count_active), $accuracy);
    }
    public function getMafiaAverageDaysSurvived($accuracy = 0)
    {
        if($this->games_count_mafia === 0)
            return 0;

        return round(($this->days_survived_mafia / $this->games_count_mafia), $accuracy);
    }
    public function getNeutralAverageDaysSurvived($accuracy = 0)
    {
        if($this->games_count_neutral === 0)
            return 0;

        return round(($this->days_survived_neutral / $this->games_count_neutral), $accuracy);
    }

    public function countGameRole($alias, $gameRole)
    {
        $this->addGame($alias);
        $this->countLightnings($alias, $gameRole);
        $this->countBanishes($alias, $gameRole);
        $this->countWinsAndRecalculateWinstreak($alias, $gameRole);
        //$this->addGamesLengthDays($alias, $gameRole);
        $this->addDaysSurvived($alias, $gameRole);
    }

    public function addGame($alias)
    {
        $alias = trim(preg_replace('<\W+>', "_", $alias), "_");
        $this->{"games_count_$alias"} += 1;
    }

    public function countLightnings($alias, $gameRole) 
    {
        if($gameRole->status->alias === 'lightning') {
            if(in_array($alias, ['no-role', 'active']))
                $this->lightnings_civilian += 1;
            else
                $this->lightnings_other += 1;
        } 
    }

    public function countBanishes($alias, $gameRole)
    {
        if(in_array($alias, ['no-role', 'active'])) {
            if($gameRole->status->alias === 'expelled')
                $this->banished_civilian += 1;
        }
    }

    public function countWinsAndRecalculateWinstreak($alias, $gameRole)
    {
        if(
            $gameRole->status->alias !== 'lightning' 
            && in_array($gameRole->faction_id, $gameRole->game->winners->pluck('faction_id')->toArray())
        ) {
            $alias = trim(preg_replace('<\W+>', "_", $alias), "_");
            $this->{"wins_$alias"} += 1;
            $this->raiseWinstreak();
        } else
            $this->flushCurrentWinstreak();
    }

    public function raiseWinstreak()
    {
        $this->current_winstreak += 1;
        if($this->current_winstreak > $this->maximal_winstreak)
            $this->maximal_winstreak = $this->current_winstreak;
    }

    public function flushCurrentWinstreak()
    {
        $this->current_winstreak = 0;
    }

    public function addDaysSurvived($alias, $gameRole)
    {
        $alias = trim(preg_replace('<\W+>', "_", $alias), "_");
        if($gameRole->status->alias !== 'survivor')
            $this->{"days_survived_$alias"} += $gameRole->day;
        else
            $this->{"days_survived_$alias"} += $gameRole->game->length;
    }

    public function addGamesLengthDays($alias, $gameRole)
    {
        $alias = trim(preg_replace('<\W+>', "_", $alias), "_");
        $this->{"days_game_length_$alias"} += $gameRole->game->length;
    }
}
