<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_form_bets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_form_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('game_id')->nullable();
            $table->json('scores')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_form_bets');
    }
};
