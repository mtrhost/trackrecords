<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_winners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('game_id')->index();
            $table->unsignedInteger('faction_id')->index();
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('faction_id')->references('id')->on('factions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('game_winners', function (Blueprint $table) {
            $table->dropForeign('game_id');
            $table->dropForeign('faction_id');
        });
        Schema::dropIfExists('game_winners');
    }
}
