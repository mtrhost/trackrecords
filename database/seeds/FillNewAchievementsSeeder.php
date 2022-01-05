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
            DB::table('achievements')->where('id', '>', 48)->delete();
            $achievements = [
                [
                    'name' => 'Мирный житель 2018', 
                    'alias' => 'civilian2018', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим мирным жителем 2018 года',
                    'image_original' => 'achievement_mj2018.png',
                    'sort' => 804
                ],
                [
                    'name' => 'Актив мж 2018', 
                    'alias' => 'active2018', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим активом мж 2018 года',
                    'image_original' => 'achievement_active2018.png',
                    'sort' => 805
                ],
                [
                    'name' => 'Мафия 2018', 
                    'alias' => 'mafia2018', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшей мафией 2018 года',
                    'image_original' => 'achievement_mafia2018.png',
                    'sort' => 806
                ],
                [
                    'name' => 'Маньяк 2018', 
                    'alias' => 'neutral2018', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим маньяком 2018 года',
                    'image_original' => 'achievement_man2018.png',
                    'sort' => 807
                ],
                [
                    'name' => 'Мирный житель 2019', 
                    'alias' => 'civilian2019', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим мирным жителем 2019 года',
                    'image_original' => 'achievement_mj2019.png',
                    'sort' => 808
                ],
                [
                    'name' => 'Актив мж 2019', 
                    'alias' => 'active2019', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим активом мж 2019 года',
                    'image_original' => 'achievement_active2019.png',
                    'sort' => 809
                ],
                [
                    'name' => 'Мафия 2019', 
                    'alias' => 'mafia2019', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшей мафией 2019 года',
                    'image_original' => 'achievement_mafia2019.png',
                    'sort' => 810
                ],
                [
                    'name' => 'Маньяк 2019', 
                    'alias' => 'neutral2019', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим маньяком 2019 года',
                    'image_original' => 'achievement_man2019.png',
                    'sort' => 811
                ],
                [
                    'name' => 'Мирный житель 2020', 
                    'alias' => 'civilian2020', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим мирным жителем 2020 года',
                    'image_original' => 'achievement_mj2020.png',
                    'sort' => 812
                ],
                [
                    'name' => 'Актив мж 2020', 
                    'alias' => 'active2020', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим активом мж 2020 года',
                    'image_original' => 'achievement_active2020.png',
                    'sort' => 813
                ],
                [
                    'name' => 'Мафия 2020', 
                    'alias' => 'mafia2020', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшей мафией 2020 года',
                    'image_original' => 'achievement_mafia2020.png',
                    'sort' => 814
                ],
                [
                    'name' => 'Маньяк 2020', 
                    'alias' => 'neutral2020', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим маньяком 2020 года',
                    'image_original' => 'achievement_man2020.png',
                    'sort' => 815
                ],
                [
                    'name' => 'Мирный житель 2021', 
                    'alias' => 'civilian2021', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим мирным жителем 2021 года',
                    'image_original' => 'achievement_mj2021.png',
                    'sort' => 820
                ],
                [
                    'name' => 'Актив мж 2021', 
                    'alias' => 'active2021', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим активом мж 2021 года',
                    'image_original' => 'achievement_active2021.png',
                    'sort' => 821
                ],
                [
                    'name' => 'Мафия 2021', 
                    'alias' => 'mafia2021', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшей мафией 2021 года',
                    'image_original' => 'achievement_mafia2021.png',
                    'sort' => 822
                ],
                [
                    'name' => 'Маньяк 2021', 
                    'alias' => 'neutral2021', 
                    'condition' => 'Условие получения - победа в голосовании',
                    'description' => 'Этот игрок путем народного голосования признан лучшим маньяком 2021 года',
                    'image_original' => 'achievement_man2021.png',
                    'sort' => 823
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
