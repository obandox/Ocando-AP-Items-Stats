<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('matchs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('source');

            $table->bigInteger('matchId');
            $table->string('region');
            $table->string('platformId');
            $table->string('matchMode');
            $table->string('matchType');

            $table->bigInteger('matchCreation');
            $table->integer('matchDuration');

            $table->string('queueType');

            $table->integer('mapId');

            $table->string('season');
            $table->string('matchVersion');

            $table->timestamps();
        });

        Schema::create('matchs_teams', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('match_id')->nullable();
            $table->foreign('match_id')
                  ->references('id')->on('matchs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('teamId');
            $table->boolean('winner');
            $table->boolean('firstBlood');
            $table->boolean('firstTower');
            $table->boolean('firstInhibitor');
            $table->boolean('firstBaron');
            $table->boolean('firstDragon');

            $table->integer('towerKills');
            $table->integer('inhibitorKills');
            $table->integer('baronKills');
            $table->integer('dragonKills');
            $table->integer('vilemawKills');
            $table->integer('dominionVictoryScore');

            $table->timestamps();
        });


        Schema::create('matchs_participants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('match_id')->nullable();
            $table->foreign('match_id')
                  ->references('id')->on('matchs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('teamId');
            $table->integer('participantId');

            $table->integer('spell1Id');
            $table->integer('spell2Id');
            $table->integer('championId');

            $table->string('highestAchievedSeasonTier');

        //BEGIN STATS        
            $table->boolean('stats_winner');
            $table->integer('stats_champLevel');
            $table->integer('stats_item0');
            $table->integer('stats_item1');
            $table->integer('stats_item2');
            $table->integer('stats_item3');
            $table->integer('stats_item4');
            $table->integer('stats_item5');
            $table->integer('stats_item6');
            $table->integer('stats_kills');
            $table->integer('stats_deaths');
            $table->integer('stats_assists');
            $table->integer('stats_doubleKills');
            $table->integer('stats_tripleKills');
            $table->integer('stats_quadraKills');
            $table->integer('stats_pentaKills');
            $table->integer('stats_unrealKills');
            $table->integer('stats_largestKillingSpree');
            $table->integer('stats_totalDamageDealt');
            $table->integer('stats_totalDamageDealtToChampions');
            $table->integer('stats_totalDamageTaken');
            $table->integer('stats_largestCriticalStrike');
            $table->integer('stats_totalHeal');
            $table->integer('stats_minionsKilled');
            $table->integer('stats_neutralMinionsKilled');
            $table->integer('stats_neutralMinionsKilledTeamJungle');
            $table->integer('stats_neutralMinionsKilledEnemyJungle');
            $table->integer('stats_goldEarned');
            $table->integer('stats_goldSpent');
            $table->integer('stats_combatPlayerScore');
            $table->integer('stats_objectivePlayerScore');
            $table->integer('stats_totalPlayerScore');
            $table->integer('stats_totalScoreRank');
            $table->integer('stats_magicDamageDealtToChampions');
            $table->integer('stats_physicalDamageDealtToChampions');
            $table->integer('stats_trueDamageDealtToChampions');
            $table->integer('stats_visionWardsBoughtInGame');
            $table->integer('stats_sightWardsBoughtInGame');
            $table->integer('stats_magicDamageDealt');
            $table->integer('stats_physicalDamageDealt');
            $table->integer('stats_trueDamageDealt');
            $table->integer('stats_magicDamageTaken');
            $table->integer('stats_physicalDamageTaken');
            $table->integer('stats_trueDamageTaken');
            $table->boolean('stats_firstBloodKill');
            $table->boolean('stats_firstBloodAssist');
            $table->boolean('stats_firstTowerKill');
            $table->boolean('stats_firstTowerAssist');
            $table->boolean('stats_firstInhibitorKill');
            $table->boolean('stats_firstInhibitorAssist');
            $table->integer('stats_inhibitorKills');
            $table->integer('stats_towerKills');
            $table->integer('stats_wardsPlaced');
            $table->integer('stats_wardsKilled');
            $table->integer('stats_largestMultiKill');
            $table->integer('stats_killingSprees');
            $table->integer('stats_totalUnitsHealed');
            $table->integer('stats_totalTimeCrowdControlDealt');

        //END STATS 

        //BEGIN TIMELINE        
            $table->string('timeline_role');
            $table->string('timeline_lane');

            //creepsPerMinDeltas
            $table->double('timeline_creepsPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_creepsPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_creepsPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_creepsPerMinDeltas_thirtyToEnd',7,3);

            //xpPerMinDeltas
            $table->double('timeline_xpPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_xpPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_xpPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_xpPerMinDeltas_thirtyToEnd',7,3);

            //goldPerMinDeltas
            $table->double('timeline_goldPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_goldPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_goldPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_goldPerMinDeltas_thirtyToEnd',7,3);

            //csDiffPerMinDeltas
            $table->double('timeline_csDiffPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_csDiffPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_csDiffPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_csDiffPerMinDeltas_thirtyToEnd',7,3);

            //xpDiffPerMinDeltas
            $table->double('timeline_xpDiffPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_xpDiffPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_xpDiffPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_xpDiffPerMinDeltas_thirtyToEnd',7,3);

            //damageTakenPerMinDeltas
            $table->double('timeline_damageTakenPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_damageTakenPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_damageTakenPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_damageTakenPerMinDeltas_thirtyToEnd',7,3);

            //damageTakenDiffPerMinDeltas
            $table->double('timeline_damageTakenDiffPerMinDeltas_zeroToTen',7,3);
            $table->double('timeline_damageTakenDiffPerMinDeltas_tenToTwenty',7,3);
            $table->double('timeline_damageTakenDiffPerMinDeltas_twentyToThirty',7,3);
            $table->double('timeline_damageTakenDiffPerMinDeltas_thirtyToEnd',7,3);

        //END TIMELINE        

            $table->timestamps();
        });


        Schema::create('matchs_participants_masteries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('match_participant_id')->nullable();
            $table->foreign('match_participant_id')
                  ->references('id')->on('matchs_participants')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->integer('masteryId');
            $table->integer('rank');

            $table->timestamps();
        });


        Schema::create('matchs_participantIdentities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('match_id')->nullable();
            $table->foreign('match_id')
                  ->references('id')->on('matchs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');


            $table->integer('participantId');

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
        Schema::drop('matchs');
        Schema::drop('matchs_teams');
        Schema::drop('matchs_participants');
        Schema::drop('matchs_participants_masteries');
        Schema::drop('matchs_participantIdentities');


    }
}
