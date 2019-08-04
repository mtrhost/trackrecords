<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setting_id')->index();
            $table->unsignedInteger('role_id')->index();
            $table->unsignedInteger('faction_id')->index();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('setting_roles', function (Blueprint $table) {
            $table->dropForeign('setting_id');
            $table->dropForeign('role_id');
            $table->dropForeign('faction_id');
        });
        Schema::dropIfExists('setting_roles');
    }
}
