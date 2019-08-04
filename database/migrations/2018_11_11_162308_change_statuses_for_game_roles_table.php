<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusesForGameRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_roles', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->index()->nullable()->after('status');
            $table->unsignedInteger('time_status_id')->index()->nullable()->after('time_status');

            $table->foreign('status_id', 'game_roles_status_foreign')->references('id')->on('game_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('time_status_id', 'game_roles_time_status_foreign')->references('id')->on('game_time_statuses')->onUpdate('cascade')->onDelete('cascade');
        });

        Artisan::call('db:seed', [
            '--class' => 'TransferGameRolesStatusesSeeder'
        ]);

        Schema::table('game_roles', function (Blueprint $table) {
            $table->dropColumn(['status', 'time_status']);
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
