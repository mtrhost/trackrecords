<?php

namespace App\Console\Commands;

use App\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertPlayerProfileLinksToNewFormat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:player-profile-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::beginTransaction();
        $list = Player::get();
        foreach ($list as $player) {
            if (!is_null($player->profile)) {
                $player->profile = preg_replace('/index\.php\?showuser\=([0-9]*)$/', 'profile/$1/', $player->profile);
                if (!$player->save()) {
                    DB::rollback();
                    break;
                }
                $this->info('Player ' . $player->name . ' profile link updated');
            }
        }
        DB::commit();

        return 0;
    }
}
