<?php

namespace App;

use App\Services\TopResult;
use Illuminate\Database\Eloquent\Model;

class PlayerAchievement extends Model
{
    protected $fillable = [
        'player_id', 'achievement_id'
    ];

    protected $casts = [
        'player_id' => 'integer',
        'achievement_id' => 'integer',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class, 'achievement_id', 'id');
    }

    public static function synchronizeAchievements($players)
    {
        $topResults = new TopResult();
        $achievements = Achievement::select('id', 'alias')->get();
        foreach($players as &$player) {
            if(!empty($player->statistics)) {
                if(!$player->assignStaticAchievements($achievements))
                    return false;

                if($player->statistics->games_count > 20) {
                    if(!$player->assignRegularAchievements($achievements))
                        return false;
                        
                    $topResults->countNeutralWinrateResult($player);
                    $topResults->countMafiaWinrateResult($player);
                    $topResults->countActiveWinrateResult($player);
                    $topResults->countNoRoleWinrateResult($player);
                    $topResults->countWinrate($player);
                    $topResults->countRoleRate($player);
                    $topResults->countCityNegativeActionsRate($player);
                    $topResults->countAverageMafiaDaysSurvivedRate($player);
                    $topResults->countWinstreak($player);
                }
            }
        }
        if(!self::assignUniqueAchievements($topResults, $achievements))
            return false;

        return true;
    }

    public static function assignUniqueAchievements($topResults, $achievements)
    {
        foreach($topResults->get() as $key => $topResult) {
            if(!self::create(['player_id' => $topResult['player'], 'achievement_id' => $achievements->filter(function($value) use($key) { return $value->alias === $key; })->first()->id]))
                return false;
        }

        return true;
    }
}
