<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\GameWinner;

class DatabaseController extends Controller
{
    public function convertGameDates()
    {
        $games = Game::select('id', 'old_date')->get();
        
        DB::transaction(function () use ($games) {
            $dickheadRussianMonths = ['Дек' => 'Dec', 'Янв' => 'Jan', 'Фев' => 'Feb', 'Мар' => 'Mar', 'Апр' => 'Apr', 'Май' => 'May', 
            'Июн' => 'Jun', 'Июл' => 'Jul', 'Авг' => 'Aug', 'Сен' => 'Sep', 'Окт' => 'Oct', 'Ноя' => 'Nov'];
            foreach($games as &$game) {
                foreach($dickheadRussianMonths as $dickhead => $trueguy) {
                    if(strpos($game->old_date, $dickhead)) {
                        $game->old_date = str_replace($dickhead, $trueguy, $game->old_date);
                    }
                }
                $game->date = Carbon::createFromFormat('d M Y H:i', strtolower(iconv('utf-8', 'cp1251', $game->old_date)), 'Asia/Novosibirsk');
                $game->date->setTimezone('Europe/Moscow');
                if(!$game->save()) {
                    DB::rollback();
                    return false;
                }
            }
            return true;
        });
    }

    public function addActiveRolesToGameWinners()
    {
        $winners = GameWinner::select('id', 'game_id', 'faction_id')->get();
        
        return response()->json(DB::transaction(function () use ($winners) {
            foreach($winners as &$winner) {
                if(IntVal($winner->faction_id) === 1) {
                    $activeWinner = new GameWinner();
                    $activeWinner->game_id = $winner->game_id;
                    $activeWinner->faction_id = 2;
                    if(!$activeWinner->save()) {
                        DB::rollback();
                        return false;
                    }
                }
            }
            return true;
        }));
    }
}
