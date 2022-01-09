<?php

namespace App\Services\Player;

use App\Dictionaries\Player\PlayerStatusDictionary;
use App\Models\Player\Player;
use App\Models\Player\PlayerAchievement;
use App\Services\TopResult;
use Illuminate\Support\Collection;

/**
 * PlayerAchievementService
 *
 * @author jcshow
 * @package App\Services\Player
 */
class PlayerAchievementService
{
    public static $staticAchievements = [
        60 => ['civilian2011', 'neutral2011', 'active2012', 'active2013'],
        6 => ['active2011'],
        46 => ['mafia2011'],
        8 => ['neutral2011'],
        11 => ['civilian2012', 'civilian2016', 'civilian2021', 'active2021'],
        13 => ['mafia2012', 'civilian2013', 'mafia2013', 'civilian2014', 'active2015'],
        10 => ['neutral2012', 'mafia2014', 'mafia2021'],
        100 => ['civilian2013'],
        102 => ['neutral2013', 'neutral2014', 'active2017'],
        21 => ['civilian2014', 'civilian2015'],
        95 => ['active2014'],
        207 => ['mafia2015', 'mafia2016', 'mafia2020'],
        218 => ['neutral2015', 'mafia2017'],
        111 => ['active2016'],
        85 => ['active2016'],
        228 => ['neutral2016'],
        183 => ['civilian2017', 'active2018'],
        269 => ['neutral2017'],
        212 => ['mindgames', 'neutral2018'],
        254 => ['civilian2018'],
        263 => ['active2018', 'neutral2020'],
        278 => ['mafia2018'],
        252 => ['neutral2018', 'civilian2019'],
        16 => ['civilian2019', 'active2019', 'civilian2020'],
        259 => ['mafia2019', 'mafia2021'],
        106 => ['neutral2019'],
        261 => ['civilian2020', 'active2020'],
        302 => ['neutral2021'],
    ];

    /**
     * Function checking if player should have regular achievements and assigning ones if needed
     * 
     * @param Player $player
     * @param Collection $achievements
     * 
     * @return bool
     */
    public function assignRegularAchievements(Player $player, Collection $achievements): bool
    {
        if ($player->status === PlayerStatusDictionary::SCAMMER) {
            return false;
        }

        if($player->games_mastered_count > 9) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'master'; })->first()->id))
                return false;
        }

        if(empty($player->statistics))
            return true;

        if($player->statistics->wins_neutral > 0) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'ghost'; })->first()->id))
                return false;
        }
        if($player->statistics->wins_mafia > 4) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'leery'; })->first()->id))
                return false;
        }
        if($player->statistics->wins_active > 9) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'sheriff'; })->first()->id))
                return false;
        }
        if(($player->statistics->games_count + $player->games_mastered_count) > 99) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'oldschool'; })->first()->id))
                return false;
        }
        if($player->statistics->games_count_mafia > 10 && $player->getFactionWinrate('mafia') > 60) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'bewatcher'; })->first()->id))
                return false;
        }
        if($player->statistics->games_count_active > 15 && $player->getFactionWinrate('active') > 65) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value){ return $value->alias === 'cityLegend'; })->first()->id))
                return false;
        }
        return true;
    }

    /**
     * Function checking if player should have static achievements and assigning ones if needed
     * 
     * @param Player $player
     * @param Collection $achievements
     * 
     * @return bool
     */
    public function assignStaticAchievements(Player $player, Collection $achievements): bool
    {
        if ($player->status === PlayerStatusDictionary::SCAMMER) {
            return false;
        }

        $staticAchievements = self::$staticAchievements;
        if (array_key_exists($player->id, $staticAchievements)) {
            if(!$player->achievements()->syncWithoutDetaching($achievements->filter(function($value) use ($staticAchievements, $player) { 
                return in_array($value->alias, $staticAchievements[$player->id]); })->pluck('id')->toArray())
            )
                return false;
        }

        return true;
    }

    /**
     * Function assigns unique achievements based on top results service
     * 
     * @param TopResult $topResults
     * @param Collection $achievements
     * 
     * @return bool
     */
    public function assignUniqueAchievements(TopResult $topResults, Collection $achievements): bool
    {
        foreach($topResults->get() as $key => $topResult) {
            if(!PlayerAchievement::create(['player_id' => $topResult['player'], 'achievement_id' => $achievements->filter(function($value) use($key) { return $value->alias === $key; })->first()->id]))
                return false;
        }

        return true;
    }

    /**
     * Function assigns unique achievements based on top results service
     * 
     * @param Collection $achievements
     * 
     * @return bool
     */
    public function assignCaesarAchievement(Collection $achievements): bool
    {
        $caesar = ['id' => null, 'count' => 0];
        $players = Player::whereHas('achievements')
            ->where('status', PlayerStatusDictionary::ACTIVE)
            ->withCount('achievements')->get();
        foreach ($players as $player) {
            if ($player->achievements_count > $caesar['count']) {
                $caesar = ['id' => $player->id, 'count' => $player->achievements_count];
            }
        }
        if (!PlayerAchievement::create(['player_id' => $caesar['id'], 'achievement_id' => $achievements->filter(function($value) { return $value->alias === 'caesar'; })->first()->id])) {
            return false;
        }

        return true;
    }
}
