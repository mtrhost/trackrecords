<?php

namespace App\Services;

use App\Models\Game\GameRole;
use App\Models\Player\Player;
use App\Models\Player\PlayerPartner;
use stdClass;

class PlayerPartnerService
{
    /**
     * @var Collection
     */
    public $partners;

    public function __construct()
    {
        $this->partners = collect();
    }

    /**
     * @param Player $player
     * @param GameRole $gameRole
     */
    public function countGameRole(Player $player, GameRole $gameRole)
    {
        $game = $gameRole->game;
        $winners = $game->winners->pluck('faction_id')->toArray();
        $players = $game->roles()->where('id', '<>', $gameRole->id)->with('player')->get();
        foreach ($players as $two) {
            $row = $this->partners->filter(static function ($partner) use ($player, $two) {
                return $partner->player_one_id === $player->id && $partner->player_two_id === $two->player->id;
            });
            $sameFaction = false;
			if (
				($gameRole->faction_id === 1 && $two->faction_id === 2)
				|| ($gameRole->faction_id === 2 && $two->faction_id === 1)
				|| ($gameRole->faction_id === $two->faction_id)
            ) {
                $sameFaction = true;
            }
            $win = false;
            if (
                in_array($gameRole->faction_id, $winners) && in_array($two->faction_id, $winners)
            ) {
                $win = true;
            }
            if ($row->isEmpty()) {
                $partner = new stdClass();
                $partner->player_one_id = $player->id;
                $partner->player_two_id = $two->player->id;
                $partner->games_count = $sameFaction === true ? 1 : 0;
                $partner->wins_count = $win === true ? 1 : 0;
                $this->partners->add($partner);
            } else {
                $this->partners->map(static function($partner) use ($win, $sameFaction, $player, $two) {
                    if ($partner->player_one_id === $player->id && $partner->player_two_id === $two->player->id) {
						if ($sameFaction) {
							$partner->games_count += 1;
						}
                        if ($win) {
                            $partner->wins_count += 1;
                        }
                    }
                    
                    return $partner;
                });
            }
        }
    }

    /**
     * @return bool
     */
    public function saveFromPartners(): bool
    {
        foreach ($this->partners as $partner) {
            $model = new PlayerPartner((array) $partner);
            if (! $model->save()) {
                return false;
            }
        }

        return true;
    }
}
