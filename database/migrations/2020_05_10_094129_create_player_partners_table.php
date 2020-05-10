<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_one_id');
            $table->unsignedInteger('player_two_id');
            $table->unsignedInteger('games_count')->default(0);
            $table->unsignedInteger('wins_count')->default(0);
            $table->timestamps();
           
            $table->unique(['player_one_id', 'player_two_id']);
            $table->foreign('player_one_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_two_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_partners', function (Blueprint $table) {
            $table->dropForeign('player_one_id');
            $table->dropForeign('player_two_id');
        });
        Schema::dropIfExists('player_partners');
    }
}
