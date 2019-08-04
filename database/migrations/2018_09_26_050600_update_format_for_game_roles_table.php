<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\GameRole;

class UpdateFormatForGameRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_roles', function (Blueprint $table) {
            $table->enum('status', GameRole::STATUS)->default(1)->after('faction_id');
            $table->unsignedInteger('day')->nullable()->after('status');
            $table->enum('time_status', GameRole::TIME_STATUS)->nullable()->after('day');
            //$table->dropColumn('sort');
        });

        Artisan::call('db:seed', [
            '--class' => 'ConvertGameRolesSeeder'
        ]);

        Schema::table('game_roles', function (Blueprint $table) {
            $table->dropColumn('stats_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
