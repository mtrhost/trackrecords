<?php

use Illuminate\Database\Seeder;
use App\Achievement;
use Illuminate\Support\Facades\DB;

class FillAchievementsAliases extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $achievements = Achievement::get();
            $aliases = [
                1 => ['alias' => 'ghost'], 2 => ['alias' => 'leery'], 3 => ['alias' => 'master'], 4 => ['alias' => 'sheriff'], 5 => ['alias' => 'oldschool'],
                6 => ['alias' => 'bewatcher'], 7 => ['alias' => 'cityLegend'], 8 => ['alias' => 'samurai'], 9 => ['alias' => 'don'], 10 => ['alias' => 'trueDetective'],
                11 => ['alias' => 'king'], 12 => ['alias' => 'speeker'], 13 => ['alias' => 'lucky'], 14 => ['alias' => 'devilsAdvocate'], 15 => ['alias' => 'survivor'],
                16 => ['alias' => 'winstreak'], 17 => ['alias' => 'civilian2011'], 18 => ['alias' => 'active2011'], 19 => ['alias' => 'mafia2011'], 20 => ['alias' => 'neutral2011'],
                21 => ['alias' => 'civilian2012'], 22 => ['alias' => 'active2012'], 23 => ['alias' => 'mafia2012'], 24 => ['alias' => 'neutral2012'], 25 => ['alias' => 'civilian2013'],
                26 => ['alias' => 'active2013'], 27 => ['alias' => 'mafia2013'], 28 => ['alias' => 'neutral2013'], 29 => ['alias' => 'civilian2014'], 30 => ['alias' => 'active2014'],
                31 => ['alias' => 'mafia2014'], 32 => ['alias' => 'neutral2014'], 33 => ['alias' => 'civilian2015'], 34 => ['alias' => 'active2015'], 35 => ['alias' => 'mafia2015'],
                36 => ['alias' => 'neutral2015'], 37 => ['alias' => 'civilian2016'], 38 => ['alias' => 'active2016'], 39 => ['alias' => 'mafia2016'], 40 => ['alias' => 'neutral2016'],
                41 => ['alias' => 'civilian2017'], 42 => ['alias' => 'active2017'], 43 => ['alias' => 'mafia2017'], 44 => ['alias' => 'neutral2017'], 45 => ['alias' => 'mindgames'],
            ];
            foreach($achievements as $achievement) {
                $achievement->alias = $aliases[$achievement->id]['alias'];
                if(!$achievement->save()) {
                    DB::rollback();
                    return false;
                }
            }
        });
    }
}
