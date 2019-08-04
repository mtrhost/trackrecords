<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id')->index();
            $table->unsignedInteger('achievement_id')->index();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('achievement_id')->references('id')->on('achievements')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign('player_id');
            $table->dropForeign('achievement_id');
        });
        Schema::dropIfExists('player_achievements');
    }
}
