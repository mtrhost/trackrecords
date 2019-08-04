<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Player;
use App\PlayerAchievement;
use Illuminate\Support\Facades\DB;

class FillPlayersAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievements:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign achievements to players via player achievements table';

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
                'statistics'
            ])
            ->withCount(['gamesMastered'])
            ->get();

        $transaction = DB::transaction(function () use($players) {
            if(!PlayerAchievement::query()->truncate() || !PlayerAchievement::synchronizeAchievements($players)) {
                DB::rollback();
                return false;
            }
            return true;
        });

        if($transaction)
            echo "Пересчет достижений успешно завершен \r\n";
        else
            echo "Пересчет достижений отменен из-за ошибки \r\n";
    }
}
