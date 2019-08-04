<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setting_id')->index();
            $table->unsignedInteger('master_id')->index();
            $table->string('name');
            $table->unsignedInteger('number');
            $table->unsignedInteger('length');
            $table->date('date')->nullable();
            $table->string('link')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('players_count');
            $table->unsignedInteger('active_count');
            $table->unsignedInteger('mafia_count');
            $table->unsignedInteger('neutral_count');
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('master_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('setting_id');
            $table->dropForeign('master_id');
        });
        Schema::dropIfExists('games');
    }
}
