<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_stats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id');
            $table->string('source');
            $table->string('region');
            $table->string('season');
            $table->string('queue');
            $table->string('type');
            $table->string('version');



            $table->bigInteger('wins');
            $table->bigInteger('loses');

            $table->bigInteger('games');
            $table->bigInteger('timesused');


            $table->bigInteger('kills');
            $table->bigInteger('deaths');
            $table->bigInteger('assists');
            
            $table->bigInteger('pentaKills');
            $table->bigInteger('magicDamageDealtToChampions');
            $table->bigInteger('minionsKilled');
            $table->bigInteger('creeps_zeroToTen');
            $table->bigInteger('creeps_tenToTwenty');
            $table->bigInteger('creeps_twentyToThirty');
            $table->bigInteger('creeps_thirtyToEnd');
            $table->bigInteger('xp_zeroToTen');
            $table->bigInteger('xp_tenToTwenty');
            $table->bigInteger('xp_twentyToThirty');
            $table->bigInteger('xp_thirtyToEnd');
            $table->bigInteger('gold_zeroToTen');
            $table->bigInteger('gold_tenToTwenty');
            $table->bigInteger('gold_twentyToThirty');
            $table->bigInteger('gold_thirtyToEnd');
            $table->bigInteger('csDiff_zeroToTen');
            $table->bigInteger('csDiff_tenToTwenty');
            $table->bigInteger('csDiff_twentyToThirty');
            $table->bigInteger('csDiff_thirtyToEnd');
            $table->bigInteger('xpDiff_zeroToTen');
            $table->bigInteger('xpDiff_tenToTwenty');
            $table->bigInteger('xpDiff_twentyToThirty');
            $table->bigInteger('xpDiff_thirtyToEnd');
            $table->bigInteger('damageTaken_zeroToTen');
            $table->bigInteger('damageTaken_tenToTwenty');
            $table->bigInteger('damageTaken_twentyToThirty');
            $table->bigInteger('damageTaken_thirtyToEnd');
            $table->bigInteger('damageTakenDiff_zeroToTen');
            $table->bigInteger('damageTakenDiff_tenToTwenty');
            $table->bigInteger('damageTakenDiff_twentyToThirty');
            $table->bigInteger('damageTakenDiff_thirtyToEnd');
            $table->bigInteger('goldEarned');
            $table->bigInteger('goldSpent');
            $table->bigInteger('playerScore');
            $table->bigInteger('matchDuration');



            $table->unsignedBigInteger('last_match_id')->nullable();
            $table->foreign('last_match_id')
                  ->references('id')->on('matchs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('items_stats_champions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('championId');
            $table->bigInteger('champion_count');

            $table->timestamps();
        });

        Schema::create('items_stats_ranks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('rank');
            $table->bigInteger('rank_count');


            $table->timestamps();
        });
        
        Schema::create('items_stats_lanes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');


            $table->string('lane');
            $table->bigInteger('lane_count');

            $table->timestamps();
        });

        Schema::create('items_stats_roles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('role');
            $table->bigInteger('role_count');

            $table->timestamps();
        });

        Schema::create('items_stats_spells', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('item_stat_id')->nullable();
            $table->foreign('item_stat_id')
                  ->references('id')->on('items_stats')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('spellId');
            $table->bigInteger('spell_count');

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
        Schema::drop('items_stats');
        Schema::drop('items_stats_champions');
        Schema::drop('items_stats_ranks');
        Schema::drop('items_stats_lanes');
        Schema::drop('items_stats_roles');
        Schema::drop('items_stats_spells');
    }
}
