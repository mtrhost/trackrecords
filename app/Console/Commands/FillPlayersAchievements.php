<?php

namespace App\Console\Commands;

use App\Dictionaries\Player\PlayerStatusDictionary;
use App\Models\Player\Player;
use App\Models\Player\PlayerAchievement;
use Illuminate\Console\Command;
use App\Services\Player\PlayerAchievementSynchronizationService;
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
     * @var PlayerAchievementSynchronizationService
     */
    protected $syncService;

    /**
     * Create a new command instance.
     * 
     * @param PlayerAchievementSynchronizationService $syncService
     *
     * @return void
     */
    public function __construct(PlayerAchievementSynchronizationService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec('chcp 65001');
        $players = Player::select('id', 'name', 'last_game', 'status')
            ->where('status', PlayerStatusDictionary::ACTIVE)
            ->with([
                'statistics'
            ])
            ->withCount(['gamesMastered'])
            ->get();

        DB::beginTransaction();
        if(!PlayerAchievement::query()->truncate() || !$this->syncService->run($players)) {
            DB::rollback();
            $this->error("Пересчет достижений отменен из-за ошибки");
            return 0;
        }
        DB::commit();

        $this->info("Пересчет достижений успешно завершен");
        return 0;
    }
}
