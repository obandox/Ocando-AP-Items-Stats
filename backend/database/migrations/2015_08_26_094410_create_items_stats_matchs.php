<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsStatsMatchs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_stats_matchs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('matchId');
            $table->bigInteger('match_count');
            $table->bigInteger('duration');
            $table->boolean('win');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items_stats_matchs');
    }
}
