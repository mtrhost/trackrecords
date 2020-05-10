<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FactionGroup;
use App\Player;
use App\PlayerStatistics;
use App\Faction;
use App\Services\PlayerPartnerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FillStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate statistics and fill player statistics table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec('chcp 65001');
        $players = Player::select('id', 'name', 'last_game')
            ->with([
                'gameRoles' => function($q){
                    $q->select('id', 'game_id', 'player_id', 'faction_id', 'status_id', 'day', 'time_status_id')
                        ->whereHas('game', function($q){
                            $q->where('status', '')
                                ->orWhereNull('status');
                        })
                        ->with([
                            'game' => function($q) {
                                $q->select('id', 'date', 'name', 'length')
                                    ->with([
                                        'winners' => function($q){
                                            $q->select('id', 'game_id', 'faction_id')
                                                ->with([
                                                    'faction' => function($q){
                                                        $q->select('id', 'group_id');
                                                    }
                                                ]);
                                        },
                                    ]);
                            },
                            'status', 'timeStatus'
                        ]);
                },
                'lastGameMastered' => function($q){
                    $q->select('id', 'date');
                }
            ])
            ->get();

        $transaction = DB::transaction(function () use($players) {
            $factions = Faction::select('id', 'group_id')
                ->with([
                    'group' => function($q){
                        $q->select('id', 'alias');
                    }
                ])
                ->get()->toArray();
            $factionsForFilter = [];
            foreach($factions as $faction) {
                $factionsForFilter[$faction['id']] = $faction['group']['alias'];
            }
            if(!PlayerStatistics::query()->truncate()) {
                DB::rollback();
                return false;
            }
            foreach($players as &$player) {
                $playerStatistics = new PlayerStatistics();
                $partnerService = new PlayerPartnerService();
                $playerStatistics->player_id = $player->id;
                if(isset($player->gameRoles)) {
                    foreach($player->gameRoles as $gameRole) {
                        $alias = $factionsForFilter[$gameRole->faction_id];
                        $playerStatistics->countGameRole($alias, $gameRole);
                        $partnerService->countGameRole($player, $gameRole);
                        $player->countLastGameDate($gameRole);
                    }
                    if(!empty($player->lastGameMastered))
                        $player->countLastGameMastered($player->lastGameMastered);
                    if($player->save())
                        echo "Дата последней игры игрока $player->name успешно обновлена\r\n\r\n";
                    else {
                        echo "Ошибка при обновлении даты последней игры игрока $player->name \r\n";
                        DB::rollback();
                        return false;
                    }
                }
                if($playerStatistics->save())
                    echo "Статистика игрока $player->name успешно учтена\r\n";
                else {
                    echo "Ошибка при обновлении статистики игрока $player->name\r\n";
                    DB::rollback();
                    return false;
                }
                if($partnerService->saveFromPartners())
                    echo "Данные по напарникам игрока $player->name успешно учтены\r\n";
                else {
                    echo "Ошибка при обновлении данных по напарникам игрока $player->name\r\n";
                    DB::rollback();
                    return false;
                }
                unset($partnerService);
            }
            return true;
        });

        if($transaction)
            echo "Пересчет статистики успешно завершен \r\n";
        else
            echo "Пересчет статистики отменен из-за ошибки \r\n";
    }


}
