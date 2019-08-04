<?php

use Illuminate\Database\Seeder;
use App\GameRole;

class TransferGameRolesStatusesSeeder extends Seeder
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
            foreach($gameRoles as $gameRole) {
                $gameRole->status_id = $gameRole->status;
                $gameRole->time_status_id = $gameRole->time_status;
                $gameRole->save();
            }
        });
    }
}
