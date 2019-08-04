<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('game_id')->index();
            $table->unsignedInteger('player_id')->index();
            $table->unsignedInteger('role_id')->index();
            $table->unsignedInteger('faction_id')->index();
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('faction_id')->references('id')->on('factions')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_roles', function (Blueprint $table) {
            $table->dropForeign('game_id');
            $table->dropForeign('player_id');
            $table->dropForeign('role_id');
            $table->dropForeign('faction_id');
        });
        Schema::dropIfExists('game_roles');
    }
}
