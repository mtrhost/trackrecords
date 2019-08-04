<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\GameRole;

class ConvertGameRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $gameRoles = GameRole::get();
            $statusToValuesConvertTable = [
                1 => ['status' => 1, 'day' => 1, 'time_status' => 1],
                2 => ['status' => 2, 'day' => 1, 'time_status' => 2],
                3 => ['status' => 3, 'day' => 2, 'time_status' => 1],
                4 => ['status' => 1, 'day' => 2, 'time_status' => 1],
                5 => ['status' => 2, 'day' => 2, 'time_status' => 2],
                6 => ['status' => 3, 'day' => 3, 'time_status' => 1],
                7 => ['status' => 2, 'day' => 3, 'time_status' => 1],
                8 => ['status' => 1, 'day' => 3, 'time_status' => 1],
                9 => ['status' => 2, 'day' => 3, 'time_status' => 2],
                10 => ['status' => 3, 'day' => 4, 'time_status' => 1],
                11 => ['status' => 1, 'day' => 4, 'time_status' => 1],
                12 => ['status' => 2, 'day' => 4, 'time_status' => 2],
                13 => ['status' => 1, 'day' => 5, 'time_status' => 1],
                14 => ['status' => 2, 'day' => 5, 'time_status' => 2],
                15 => ['status' => 1, 'day' => 6, 'time_status' => 1],
                16 => ['status' => 2, 'day' => 6, 'time_status' => 2],
                17 => ['status' => 1, 'day' => 7, 'time_status' => 1],
                18 => ['status' => 4, 'day' => null, 'time_status' => null],
                19 => ['status' => 1, 'day' => 8, 'time_status' => 1],
                20 => ['status' => 2, 'day' => 8, 'time_status' => 2],
                21 => ['status' => 2, 'day' => 7, 'time_status' => 2],
                22 => ['status' => 3, 'day' => 5, 'time_status' => 1],
                23 => ['status' => 5, 'day' => 1, 'time_status' => 2],
                24 => ['status' => 1, 'day' => 9, 'time_status' => 1],
                25 => ['status' => 6, 'day' => 2, 'time_status' => 2],
                26 => ['status' => 6, 'day' => 6, 'time_status' => 2],
                27 => ['status' => 5, 'day' => 3, 'time_status' => 2],
                28 => ['status' => 5, 'day' => 5, 'time_status' => 2],
                29 => ['status' => 5, 'day' => 4, 'time_status' => 2],
                30 => ['status' => 7, 'day' => 2, 'time_status' => 2],
                31 => ['status' => 7, 'day' => 3, 'time_status' => 2],
                32 => ['status' => 7, 'day' => 6, 'time_status' => 2],
                33 => ['status' => 7, 'day' => 3, 'time_status' => 2],
                34 => ['status' => 7, 'day' => 5, 'time_status' => 2],
                35 => ['status' => 3, 'day' => 1, 'time_status' => 1],
                36 => ['status' => 7, 'day' => 4, 'time_status' => 1],
                37 => ['status' => 7, 'day' => 3, 'time_status' => 1],
                38 => ['status' => 2, 'day' => 7, 'time_status' => 2],
                39 => ['status' => 5, 'day' => 2, 'time_status' => 2],
                40 => ['status' => 2, 'day' => 9, 'time_status' => 2],
                41 => ['status' => 5, 'day' => 2, 'time_status' => 2],
                42 => ['status' => 6, 'day' => 1, 'time_status' => 2],
                43 => ['status' => 5, 'day' => 8, 'time_status' => 2],
                44 => ['status' => 5, 'day' => 7, 'time_status' => 2],
                45 => ['status' => 5, 'day' => 6, 'time_status' => 2],
                46 => ['status' => 5, 'day' => 7, 'time_status' => 2],
                47 => ['status' => 3, 'day' => 6, 'time_status' => 1],
                48 => ['status' => 6, 'day' => 6, 'time_status' => 2],
                49 => ['status' => 7, 'day' => 8, 'time_status' => 2],
                50 => ['status' => 7, 'day' => 9, 'time_status' => 1],
                51 => ['status' => 7, 'day' => 6, 'time_status' => 1],
                52 => ['status' => 7, 'day' => 5, 'time_status' => 1],
                53 => ['status' => 8, 'day' => 2, 'time_status' => 1],
                54 => ['status' => 2, 'day' => 4, 'time_status' => 1],
                55 => ['status' => 8, 'day' => 3, 'time_status' => 1],
                56 => ['status' => 8, 'day' => 5, 'time_status' => 1],
                57 => ['status' => 2, 'day' => 3, 'time_status' => 1],
                58 => ['status' => 7, 'day' => 7, 'time_status' => 1],
                59 => ['status' => 6, 'day' => 3, 'time_status' => 2],
                60 => ['status' => 9, 'day' => 2, 'time_status' => 2],
                61 => ['status' => 6, 'day' => 4, 'time_status' => 2],
                62 => ['status' => 8, 'day' => 5, 'time_status' => 1],
                63 => ['status' => 6, 'day' => 5, 'time_status' => 2],
                64 => ['status' => 8, 'day' => 6, 'time_status' => 1],
                65 => ['status' => 8, 'day' => 4, 'time_status' => 1],
                66 => ['status' => 2, 'day' => 2, 'time_status' => 1],
                67 => ['status' => 7, 'day' => 1, 'time_status' => 1],
                68 => ['status' => 6, 'day' => 2, 'time_status' => 2],
                69 => ['status' => 5, 'day' => 1, 'time_status' => 2],
                70 => ['status' => 6, 'day' => 4, 'time_status' => 1],
                71 => ['status' => 6, 'day' => 3, 'time_status' => 1],
                72 => ['status' => 3, 'day' => 7, 'time_status' => 1],
                73 => ['status' => 9, 'day' => 4, 'time_status' => 2],
                74 => ['status' => 7, 'day' => 1, 'time_status' => 2],
                75 => ['status' => 7, 'day' => 2, 'time_status' => 2],
                76 => ['status' => 6, 'day' => 7, 'time_status' => 2],
                77 => ['status' => 5, 'day' => 9, 'time_status' => 2],
                78 => ['status' => 7, 'day' => 3, 'time_status' => 2],
                79 => ['status' => 9, 'day' => 1, 'time_status' => 2],
                80 => ['status' => 10, 'day' => 3, 'time_status' => 2],
                81 => ['status' => 9, 'day' => 3, 'time_status' => 2],
                82 => ['status' => 7, 'day' => 4, 'time_status' => 2],
                83 => ['status' => 7, 'day' => 3, 'time_status' => 1],
                84 => ['status' => 6, 'day' => 3, 'time_status' => 1],
                85 => ['status' => 1, 'day' => 10, 'time_status' => 1],
                86 => ['status' => 8, 'day' => 9, 'time_status' => 1],
                87 => ['status' => 6, 'day' => 9, 'time_status' => 2],
                88 => ['status' => 7, 'day' => 2, 'time_status' => 1],
                89 => ['status' => 2, 'day' => 3, 'time_status' => 1],
                90 => ['status' => 9, 'day' => 4, 'time_status' => 2],
                91 => ['status' => 10, 'day' => 1, 'time_status' => 2],
                92 => ['status' => 5, 'day' => 7, 'time_status' => 1],
                93 => ['status' => 6, 'day' => 2, 'time_status' => 1],
                94 => ['status' => 5, 'day' => 3, 'time_status' => 1],
                95 => ['status' => 5, 'day' => 4, 'time_status' => 1],
                96 => ['status' => 5, 'day' => 2, 'time_status' => 1],
                97 => ['status' => 2, 'day' => 5, 'time_status' => 1],
                98 => ['status' => 2, 'day' => 10, 'time_status' => 2],
                99 => ['status' => 1, 'day' => 11, 'time_status' => 1],
                100 => ['status' => 8, 'day' => 7, 'time_status' => 1],
                101 => ['status' => 7, 'day' => 4, 'time_status' => 2],
                102 => ['status' => 7, 'day' => 6, 'time_status' => 2],
                103 => ['status' => 10, 'day' => 2, 'time_status' => 2],
                104 => ['status' => 10, 'day' => 4, 'time_status' => 2],
                105 => ['status' => 3, 'day' => 8, 'time_status' => 1],
                106 => ['status' => 6, 'day' => 5, 'time_status' => 1],
                107 => ['status' => 5, 'day' => 5, 'time_status' => 1],
                108 => ['status' => 7, 'day' => 7, 'time_status' => 2],
                109 => ['status' => 7, 'day' => 4, 'time_status' => 1],
                110 => ['status' => 3, 'day' => 10, 'time_status' => 1],
                111 => ['status' => 2, 'day' => 1, 'time_status' => 1],
                112 => ['status' => 5, 'day' => 9, 'time_status' => 2],
            ];
            foreach($gameRoles as $gr) {
                $newData = $statusToValuesConvertTable[$gr->stats_id];
                $gr->status = $newData['status']; $gr->day = $newData['day']; $gr->time_status = $newData['time_status'];
                if(!$gr->save()) {
                    DB::rollback();
                    return false;
                }
            }
        });
    }
}
