<?php

use Illuminate\Database\Seeder;
use App\GameStatus;
use Illuminate\Support\Facades\DB;
use App\GameTimeStatus;

class FillGameStatuses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $statuses = [
                ['name' => 'Выгнан', 'alias' => 'expelled'],
                ['name' => 'Убит мафией', 'alias' => 'killed-by-mafia'],
                ['name' => 'Молния', 'alias' => 'lightning'],
                ['name' => 'Выживший', 'alias' => 'survivor'],
                ['name' => 'Убит маньяком', 'alias' => 'killed-by-neutral'],
                ['name' => 'Убит активом', 'alias' => 'killed-by-active'],
                ['name' => 'Умер', 'alias' => 'dead'],
                ['name' => 'Умер в клетке', 'alias' => 'dead-in-cage'],
                ['name' => 'Убит мафией и активом', 'alias' => 'killed-by-mafia-and-active'],
                ['name' => 'Умер от ивента', 'alias' => 'killed-by-event'],
            ];
            if(!GameStatus::insert($statuses)) {
                DB::rollback();
                return false;
            }
            $timeStatuses = [
                ['name' => 'день', 'alias' => 'day'],
                ['name' => 'ночь', 'alias' => 'night'],
            ];
            if(!GameTimeStatus::insert($statuses)) {
                DB::rollback();
                return false;
            }
        });
    }
}
