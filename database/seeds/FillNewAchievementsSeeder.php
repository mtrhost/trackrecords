<?php

use App\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FillNewAchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('achievements')->where('id', '>', 45)->delete();
            $achievements = [
                [
                    'name' => 'Ниндзя', 
                    'alias' => 'ninja', 
                    'condition' => 'Условие получения - наибольшее среднее время жизни на нейтрале',
                    'description' => 'Его движения бесшумны, силуэт тоньше катаны, а мысль убить его в головах игроков возникает реже чем съесть пиццу с ананасами',
                    'image_original' => 'achievement_18.png',
                    'sort' => 18
                ],
                [
                    'name' => 'На морозе', 
                    'alias' => 'coldFeet', 
                    'condition' => 'Условие получения - наибольшее среднее время жизни на активе мж',
                    'description' => 'Истинный шериф или деревенский дурачок? Какое то из этих качеств позволяет ему жить и пуляться абилками дольше других',
                    'image_original' => 'achievement_19.png',
                    'sort' => 19
                ],
                [
                    'name' => 'Цезарь', 
                    'alias' => 'caesar', 
                    'condition' => 'Условие получения - наибольшее количество ачивок',
                    'description' => 'Настоящий коллекционер и воплощение Финта, истинный амбидекстр и виртуозный всеборец',
                    'image_original' => 'achievement_20.png',
                    'sort' => 20
                ],
            ];
            foreach ($achievements as $achievement) {
                if(! Achievement::create($achievement)) {
                    DB::rollback();
                    return false;
                }
            }

            return true;
        });
    }
}
