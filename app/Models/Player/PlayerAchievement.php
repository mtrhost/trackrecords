<?php

namespace App;

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
        $dummy = ['player' => null, 'rate' => 0];
        $topResults = ['samurai' => $dummy, 'don' => $dummy, 'trueDetective' => $dummy, 'king' => $dummy, 'speeker' => $dummy, 'lucky' => $dummy, 
        'devilsAdvocate' => ['player' => null, 'rate' => 100], 'survivor' => $dummy, 'winstreak' => $dummy];
        $achievements = Achievement::select('id', 'alias')->get();
        foreach($players as &$player) {
            if(!empty($player->statistics)) {
                if(!$player->assignStaticAchievements($achievements))
                    return false;

                if($player->statistics->games_count > 20) {
                    if(!$player->assignRegularAchievements($achievements))
                        return false;
                        
                    $neutralWinrate = $player->getFactionWinrate('neutral', 2);
                    if($player->statistics->games_count_neutral > 4 && $neutralWinrate > $topResults['samurai']['rate'])
                        $topResults['samurai'] = ['player' => $player->id, 'rate' => $neutralWinrate];
                    $mafiaWinrate = $player->getFactionWinrate('mafia', 2);
                    if($player->statistics->games_count_mafia > 10 && $mafiaWinrate > $topResults['don']['rate'])
                        $topResults['don'] = ['player' => $player->id, 'rate' => $mafiaWinrate];
                    $activeWinrate = $player->getFactionWinrate('active', 2);
                    if($player->statistics->games_count_active > 15 && $activeWinrate > $topResults['trueDetective']['rate'])
                        $topResults['trueDetective'] = ['player' => $player->id, 'rate' => $activeWinrate];
                    $civilianWinrate = $player->getFactionWinrate('no-role', 2);
                    if($player->statistics->games_count_no_role > 15 && $civilianWinrate > $topResults['speeker']['rate'])
                        $topResults['speeker'] = ['player' => $player->id, 'rate' => $civilianWinrate];
                    $totalWinrate = $player->getWinrate(2);
                    if($player->statistics->games_count > 30 && $totalWinrate > $topResults['king']['rate'])
                        $topResults['king'] = ['player' => $player->id, 'rate' => $totalWinrate];
                    $roleRate = $player->getRoleRate(2);
                    if($player->statistics->games_count > 30 && $roleRate > $topResults['lucky']['rate'])
                        $topResults['lucky'] = ['player' => $player->id, 'rate' => $roleRate];
                    $failForCityRate = $player->getCityNegativeActionsRate(2);
                    if($player->statistics->civilian_games_count > 30 && $failForCityRate < $topResults['devilsAdvocate']['rate'])
                        $topResults['devilsAdvocate'] = ['player' => $player->id, 'rate' => $failForCityRate];
                    $mafiaAverageDaysSurvived = $player->getMafiaAverageDaysSurvived(2);
                    if($player->statistics->games_count_mafia > 10 && $mafiaAverageDaysSurvived > $topResults['survivor']['rate'])
                        $topResults['survivor'] = ['player' => $player->id, 'rate' => $mafiaAverageDaysSurvived];
                    if($player->statistics->games_count > 30 && $player->statistics->maximal_winstreak > $topResults['winstreak']['rate'])
                        $topResults['winstreak'] = ['player' => $player->id, 'rate' => $player->statistics->maximal_winstreak];
                }
            }
        }
        if(!self::assignUniqueAchievements($topResults, $achievements))
            return false;

        return true;
    }

    public static function assignUniqueAchievements($topResults, $achievements)
    {
        foreach($topResults as $key => $topResult) {
            if(!self::create(['player_id' => $topResult['player'], 'achievement_id' => $achievements->filter(function($value) use($key) { return $value->alias === $key; })->first()->id]))
                return false;
        }

        return true;
    }
}
