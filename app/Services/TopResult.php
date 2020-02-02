<?php

namespace App\Services;

use App\Player;
use Illuminate\Support\Str;

class TopResult
{
    private $dummy = ['player' => null, 'rate' => 0, 'lastGame' => null];

    private $devilsAdvocate = ['player' => null, 'rate' => 100, 'lastGame' => null];

    private $samurai;

    private $don;

    private $trueDetective;

    private $king;

    private $speeker;

    private $lucky;

    private $coldFeet;

    private $survivor;

    private $ninja;

    private $winstreak;

    public function __construct()
    {
        $this->samurai = $this->don = $this->trueDetective = $this->king = $this->speeker = $this->lucky = $this->survivor
            = $this->winstreak = $this->dummy;
    }

    public function get()
    {
        $result = [];
        foreach (get_object_vars($this) as $property => $value) {
            if (!in_array($property, ['dummy'])) {
                $result[$property] = $value;
            }
        }
        return $result;
    }

    public function countNeutralWinrateResult(Player $player)
    {
        $this->countFactionWinrateResult($player, 'neutral', 'samurai', 4);
    }

    public function countMafiaWinrateResult(Player $player)
    {
        $this->countFactionWinrateResult($player, 'mafia', 'don', 10);
    }

    public function countActiveWinrateResult(Player $player)
    {
        $this->countFactionWinrateResult($player, 'active', 'trueDetective', 15);
    }

    public function countNoRoleWinrateResult(Player $player)
    {
        $this->countFactionWinrateResult($player, 'no-role', 'speeker', 15);
    }

    public function countWinrate(Player $player)
    {
        $rate = $player->getWinrate(2);
        $this->countPropery($player, 'king', 'games_count', 30, $rate);
    }

    public function countRoleRate(Player $player)
    {
        $rate = $player->getRoleRate(2);
        $this->countPropery($player, 'lucky', 'games_count', 30, $rate);
    }

    public function countCityNegativeActionsRate(Player $player)
    {
        $rate = $player->getCityNegativeActionsRate(2);
        if(
            $player->statistics->civilian_games_count > 30 
            && (
                $rate < $this->devilsAdvocate['rate']
                || ($rate == $this->devilsAdvocate['rate'] && $player->last_game > $this->devilsAdvocate['lastGame'])
            )
        ) {
            $this->devilsAdvocate = ['player' => $player->id, 'rate' => $rate, 'lastGame' => $player->last_game];
        }
    }

    public function countAverageActiveDaysSurvivedRate(Player $player)
    {
        $rate = $player->getActiveAverageDaysSurvived(2);
        $this->countPropery($player, 'coldFeet', 'games_count_active', 10, $rate);
    }

    public function countAverageMafiaDaysSurvivedRate(Player $player)
    {
        $rate = $player->getMafiaAverageDaysSurvived(2);
        $this->countPropery($player, 'survivor', 'games_count_mafia', 10, $rate);
    }

    public function countAverageNeutralDaysSurvivedRate(Player $player)
    {
        $rate = $player->getNeutralAverageDaysSurvived(2);
        $this->countPropery($player, 'ninja', 'games_count_neutral', 5, $rate);
    }

    public function countWinstreak(Player $player)
    {
        $this->countPropery($player, 'winstreak', 'games_count', 30, $player->statistics->maximal_winstreak);
    }

    private function countFactionWinrateResult(Player $player, string $faction, string $property, int $value)
    {
        $winrate = $player->getFactionWinrate($faction, 2);
        $this->countPropery($player, $property, 'games_count_' . trim(preg_replace('<\W+>', "_", $faction), "_"), $value, $winrate);
    }

    private function countPropery(Player $player, string $property, string $statisticsField, int $value, $winrate)
    {
        if(
            $player->statistics->{$statisticsField} > $value 
            && (
                $winrate > $this->{$property}['rate']
                || ($winrate == $this->{$property}['rate'] && $player->last_game > $this->{$property}['lastGame'])
            )
        ) {
            $this->{$property} = ['player' => $player->id, 'rate' => $winrate, 'lastGame' => $player->last_game];
        }
    }
}
