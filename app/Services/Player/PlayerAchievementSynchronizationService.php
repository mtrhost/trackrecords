<?php

namespace App\Services\Player;

use App\Models\Achievement\Achievement;
use App\Services\TopResult;
use Illuminate\Support\Collection;

/**
 * PlayerAchievementSynchronizationService
 *
 * @author jcshow
 * @package App\Services\Player
 */
class PlayerAchievementSynchronizationService
{
    /**
     * @var PlayerService
     */
    protected $playerService;

    /**
     * @var PlayerAchievementService
     */
    protected $achievementService;

    /**
     * @param PlayerService $playerService
     * @param PlayerAchievementService $achievementService
     */
    public function __construct(PlayerService $playerService, PlayerAchievementService $achievementService)
    {
        $this->playerService = $playerService;
        $this->achievementService = $achievementService;
    }

    /**
     * Function performs achievement synchronization
     * 
     * @param Collection $players
     * 
     * @return bool
     */
    public function run(Collection $players): bool
    {
        $topResults = new TopResult();
        $achievements = Achievement::select('id', 'alias')->get();
        foreach($players as &$player) {
            $topResults = $this->playerService->assignAchievements($player, $achievements, $topResults);
        }
        if(
            !$this->achievementService->assignUniqueAchievements($topResults, $achievements)
            || !$this->achievementService->assignCaesarAchievement($achievements)
        ) {
            return false;
        }

        return true;
    }
}
