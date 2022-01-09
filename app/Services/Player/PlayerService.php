<?php

namespace App\Services\Player;

use App\Models\Player\Player;
use App\Repositories\Interfaces\PDRepositoryInterface;
use App\Services\TopResult;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * PlayerService
 *
 * @author jcshow
 * @package App\Services\Player
 */
class PlayerService
{
    /**
     * @var PlayerAchievementService
     */
    protected $achievementService;

    /**
     * @var PDRepositoryInterface
     */
    protected $pdRepository;

    /**
     * @param PlayerAchievementService $achievementService
     * @param PDRepositoryInterface $pdRepository
     */
    public function __construct(PlayerAchievementService $achievementService, PDRepositoryInterface $pdRepository)
    {
        $this->achievementService = $achievementService;
        $this->pdRepository = $pdRepository;
    }

    /**
     * Function performs player profile parsing to append additional data to player
     * 
     * @param Player $player
     * 
     * @return Player
     */
    public function parseProfile(Player $player): Player
    {
        $profileData = $this->pdRepository->parseProfileData($player->profile);
        $player->last_active = isset($profileData['lastActive']) ? $profileData['lastActive'] : null;
        return $player;
    }

    /**
     * Function performs update of player avatar via parsing
     * 
     * @param Player $player
     * 
     * @return Player
     */
    public function updatePlayerAvatar(Player $player): Player
    {
        if(is_null($player->last_profile_parse_date) || Carbon::now()->subDays(3)->gt($player->last_profile_parse_date)) {
            $avatar = $this->pdRepository->parseProfileAvatar($player->profile);
            if($avatar) {
                $image = Image::make($avatar);
                //$format = preg_replace('/(.*\.)(.*)(\?.*)/', '$2', $avatar);
                $publicPath = Storage::disk('public');
                $folderPath = 'players/' . $player->id . '/';
                if (!file_exists($publicPath->path($folderPath))) {
                    mkdir($publicPath->path($folderPath), 0755, true);
                }
                $imageName = 'profileImage.png';
                $absolutePath = $publicPath->path($folderPath . $imageName);
                $result = $image->save($absolutePath, 100);
                if($result) {
                    $player->last_profile_parse_date = Carbon::now()->toDateTimeString();
                    $player->save();
                }
            }
        }

        return $player;
    }

    /**
     * Function performs achievement assignment
     * 
     * @param Player $player
     * @param Collection $achievements
     * @param TopResult $players
     * 
     * @return TopResult
     */
    public function assignAchievements(Player $player, Collection $achievements, TopResult $topResult): TopResult
    {
        if(!empty($player->statistics)) {
            if(!$this->achievementService->assignStaticAchievements($player, $achievements))
                return false;

            if($player->statistics->games_count > 20) {
                if(!$this->achievementService->assignRegularAchievements($player, $achievements))
                    return false;
                    
                if ($player->isActive()) {
                    $topResult->countNeutralWinrateResult($player);
                    $topResult->countMafiaWinrateResult($player);
                    $topResult->countActiveWinrateResult($player);
                    $topResult->countNoRoleWinrateResult($player);
                    $topResult->countWinrate($player);
                    $topResult->countRoleRate($player);
                    $topResult->countCityNegativeActionsRate($player);
                    $topResult->countAverageActiveDaysSurvivedRate($player);
                    $topResult->countAverageMafiaDaysSurvivedRate($player);
                    $topResult->countAverageNeutralDaysSurvivedRate($player);
                    $topResult->countWinstreak($player);
                }
            }
        }

        return $topResult;
    }
}
