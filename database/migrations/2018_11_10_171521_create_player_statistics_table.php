<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id')->index();
            $table->unsignedInteger('games_count_no_role')->default(0);
            $table->unsignedInteger('wins_no_role')->default(0);
            $table->unsignedInteger('games_count_active')->default(0);
            $table->unsignedInteger('wins_active')->default(0);
            $table->unsignedInteger('games_count_mafia')->default(0);
            $table->unsignedInteger('wins_mafia')->default(0);
            $table->unsignedInteger('games_count_neutral')->default(0);
            $table->unsignedInteger('wins_neutral')->default(0);
            $table->unsignedInteger('lightnings_civilian')->default(0);
            $table->unsignedInteger('lightnings_other')->default(0);
            $table->unsignedInteger('banished_civilian')->default(0);
            $table->unsignedInteger('maximal_winstreak')->default(0);
            $table->unsignedInteger('current_winstreak')->default(0);
            $table->unsignedInteger('days_survived_no_role')->default(0);
            $table->unsignedInteger('days_survived_active')->default(0);
            $table->unsignedInteger('days_survived_mafia')->default(0);
            $table->unsignedInteger('days_survived_neutral')->default(0);
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
