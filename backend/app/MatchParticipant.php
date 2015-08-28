<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MatchParticipant extends Model 
{

    protected $table = 'matchs_participants';

    protected $fillable = [
            'match_id',
            'teamId',
            'participantId',
            'spell1Id',
            'spell2Id',
            'championId',
            'highestAchievedSeasonTier',
            'stats_winner',
            'stats_champLevel',
            'stats_item0',
            'stats_item1',
            'stats_item2',
            'stats_item3',
            'stats_item4',
            'stats_item5',
            'stats_item6',
            'stats_kills',
            'stats_deaths',
            'stats_assists',
            'stats_doubleKills',
            'stats_tripleKills',
            'stats_quadraKills',
            'stats_pentaKills',
            'stats_unrealKills',
            'stats_largestKillingSpree',
            'stats_totalDamageDealt',
            'stats_totalDamageDealtToChampions',
            'stats_totalDamageTaken',
            'stats_largestCriticalStrike',
            'stats_totalHeal',
            'stats_minionsKilled',
            'stats_neutralMinionsKilled',
            'stats_neutralMinionsKilledTeamJungle',
            'stats_neutralMinionsKilledEnemyJungle',
            'stats_goldEarned',
            'stats_goldSpent',
            'stats_combatPlayerScore',
            'stats_objectivePlayerScore',
            'stats_totalPlayerScore',
            'stats_totalScoreRank',
            'stats_magicDamageDealtToChampions',
            'stats_physicalDamageDealtToChampions',
            'stats_trueDamageDealtToChampions',
            'stats_visionWardsBoughtInGame',
            'stats_sightWardsBoughtInGame',
            'stats_magicDamageDealt',
            'stats_physicalDamageDealt',
            'stats_trueDamageDealt',
            'stats_magicDamageTaken',
            'stats_physicalDamageTaken',
            'stats_trueDamageTaken',
            'stats_firstBloodKill',
            'stats_firstBloodAssist',
            'stats_firstTowerKill',
            'stats_firstTowerAssist',
            'stats_firstInhibitorKill',
            'stats_firstInhibitorAssist',
            'stats_inhibitorKills',
            'stats_towerKills',
            'stats_wardsPlaced',
            'stats_wardsKilled',
            'stats_largestMultiKill',
            'stats_killingSprees',
            'stats_totalUnitsHealed',
            'stats_totalTimeCrowdControlDealt',    
            'timeline_role',
            'timeline_lane',
            'timeline_creepsPerMinDeltas_zeroToTen',
            'timeline_creepsPerMinDeltas_tenToTwenty',
            'timeline_creepsPerMinDeltas_twentyToThirty',
            'timeline_creepsPerMinDeltas_thirtyToEnd',
            'timeline_xpPerMinDeltas_zeroToTen',
            'timeline_xpPerMinDeltas_tenToTwenty',
            'timeline_xpPerMinDeltas_twentyToThirty',
            'timeline_xpPerMinDeltas_thirtyToEnd',
            'timeline_goldPerMinDeltas_zeroToTen',
            'timeline_goldPerMinDeltas_tenToTwenty',
            'timeline_goldPerMinDeltas_twentyToThirty',
            'timeline_goldPerMinDeltas_thirtyToEnd',
            'timeline_csDiffPerMinDeltas_zeroToTen',
            'timeline_csDiffPerMinDeltas_tenToTwenty',
            'timeline_csDiffPerMinDeltas_twentyToThirty',
            'timeline_csDiffPerMinDeltas_thirtyToEnd',
            'timeline_xpDiffPerMinDeltas_zeroToTen',
            'timeline_xpDiffPerMinDeltas_tenToTwenty',
            'timeline_xpDiffPerMinDeltas_twentyToThirty',
            'timeline_xpDiffPerMinDeltas_thirtyToEnd',
            'timeline_damageTakenPerMinDeltas_zeroToTen',
            'timeline_damageTakenPerMinDeltas_tenToTwenty',
            'timeline_damageTakenPerMinDeltas_twentyToThirty',
            'timeline_damageTakenPerMinDeltas_thirtyToEnd',
            'timeline_damageTakenDiffPerMinDeltas_zeroToTen',
            'timeline_damageTakenDiffPerMinDeltas_tenToTwenty',
            'timeline_damageTakenDiffPerMinDeltas_twentyToThirty',
            'timeline_damageTakenDiffPerMinDeltas_thirtyToEnd',
    ];



    protected $casts = [
        'id' => 'integer', 
    ];



    public function match()
    {
        return $this->belongsTo('Match','match_id');
    }

}
