<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ItemStat extends Model 
{

    protected $table = 'items_stats';

    protected $fillable = [
            'item_id',
            'source',
            'region',
            'season',
            'queue',
            'type',
            'version',
            
            'wins',
            'loses',
            'games',
            'timesused',

            'kills',
            'deaths',
            'assists',

            'pentaKills',
            'magicDamageDealtToChampions',
            'minionsKilled',
            'creeps_zeroToTen',
            'creeps_tenToTwenty',
            'creeps_twentyToThirty',
            'creeps_thirtyToEnd',
            'xp_zeroToTen',
            'xp_tenToTwenty',
            'xp_twentyToThirty',
            'xp_thirtyToEnd',
            'gold_zeroToTen',
            'gold_tenToTwenty',
            'gold_twentyToThirty',
            'gold_thirtyToEnd',
            'csDiff_zeroToTen',
            'csDiff_tenToTwenty',
            'csDiff_twentyToThirty',
            'csDiff_thirtyToEnd',
            'xpDiff_zeroToTen',
            'xpDiff_tenToTwenty',
            'xpDiff_twentyToThirty',
            'xpDiff_thirtyToEnd',
            'damageTaken_zeroToTen',
            'damageTaken_tenToTwenty',
            'damageTaken_twentyToThirty',
            'damageTaken_thirtyToEnd',
            'damageTakenDiff_zeroToTen',
            'damageTakenDiff_tenToTwenty',
            'damageTakenDiff_twentyToThirty',
            'damageTakenDiff_thirtyToEnd',
            'goldEarned',
            'goldSpent',
            'playerScore',
            'matchDuration',
            'last_match_id',
    ];


    protected $casts = [
        'id' => 'integer', 
    ];

    public static $_itemCache = [];
    public static function getItem($itemId){
        if(!isset(self::$_itemCache[$itemId])){
            self::$_itemCache[$itemId] = Item::where(['itemId'=>$itemId])->first();
        }
        return self::$_itemCache[$itemId];
    }
    
    public static function calc($match, $participant, $itemId){

        $item = self::getItem($itemId);
        if(!$item) return null;

        $source = $match->source;
        $region = $match->region;
        $season = $match->season;
        $queue = $match->queueType;
        $type = $match->matchType;
        $version = $match->matchVersion;

        $itemStat = ItemStat::firstOrCreate([
            'item_id' => $item->id ,
            'source' => $source,
            'region' => $region,
            'season' => $season,
            'queue' => $queue,
            'type' => $type,
            'version' => $version,
        ]);

        //COUNT GAMES BEGIN
        $itemStatMatch = ItemStatMatch::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'matchId' => $match->matchId,
        ]);
        if(!$itemStatMatch->match_count)
            $itemStatMatch->match_count = 0;
        $itemStatMatch->match_count += 1;
        if($participant->stats_winner){
            $itemStatMatch->win = true;
        }
        $itemStatMatch->duration = $match->matchDuration;
        $itemStatMatch->save();

        $itemStat->wins = ItemStatMatch::where(['item_stat_id' => $itemStat->id, 'win'=>true ])->count();
        $itemStat->games = ItemStatMatch::where(['item_stat_id' => $itemStat->id])->count();
        $itemStat->loses = $itemStat->games - $itemStat->wins;
        $itemStat->matchDuration = ItemStatMatch::where(['item_stat_id' => $itemStat->id])->sum('duration');

        //COUNT GAMES END

        $itemStat->timesused += 1; 

        $itemStat->kills += $participant->stats_kills;
        $itemStat->deaths += $participant->stats_deaths;
        $itemStat->assists += $participant->stats_assists;
        $itemStat->pentaKills += $participant->stats_pentaKills;
        $itemStat->magicDamageDealtToChampions += $participant->stats_magicDamageDealtToChampions;
        $itemStat->minionsKilled += $participant->stats_minionsKilled;

        $itemStat->creeps_zeroToTen += $participant->timeline_creepsPerMinDeltas_zeroToTen * 10;
        $itemStat->creeps_tenToTwenty += $participant->timeline_creepsPerMinDeltas_zeroToTen * 10 ;
        $itemStat->creeps_twentyToThirty += $participant->timeline_creepsPerMinDeltas_zeroToTen * 10;
        $itemStat->creeps_thirtyToEnd += $participant->timeline_creepsPerMinDeltas_zeroToTen * $match->matchDuration/60 ;


        $itemStat->xp_zeroToTen += $participant->timeline_xpPerMinDeltas_zeroToTen * 10;
        $itemStat->xp_tenToTwenty += $participant->timeline_xpPerMinDeltas_zeroToTen * 10 ;
        $itemStat->xp_twentyToThirty += $participant->timeline_xpPerMinDeltas_zeroToTen * 10;
        $itemStat->xp_thirtyToEnd += $participant->timeline_xpPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->gold_zeroToTen += $participant->timeline_goldPerMinDeltas_zeroToTen * 10;
        $itemStat->gold_tenToTwenty += $participant->timeline_goldPerMinDeltas_zeroToTen * 10 ;
        $itemStat->gold_twentyToThirty += $participant->timeline_goldPerMinDeltas_zeroToTen * 10;
        $itemStat->gold_thirtyToEnd += $participant->timeline_goldPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->csDiff_zeroToTen += $participant->timeline_csDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->csDiff_tenToTwenty += $participant->timeline_csDiffPerMinDeltas_zeroToTen * 10 ;
        $itemStat->csDiff_twentyToThirty += $participant->timeline_csDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->csDiff_thirtyToEnd += $participant->timeline_csDiffPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->xpDiff_zeroToTen += $participant->timeline_xpDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->xpDiff_tenToTwenty += $participant->timeline_xpDiffPerMinDeltas_zeroToTen * 10 ;
        $itemStat->xpDiff_twentyToThirty += $participant->timeline_xpDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->xpDiff_thirtyToEnd += $participant->timeline_xpDiffPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->damageTaken_zeroToTen += $participant->timeline_damageTakenPerMinDeltas_zeroToTen * 10;
        $itemStat->damageTaken_tenToTwenty += $participant->timeline_damageTakenPerMinDeltas_zeroToTen * 10 ;
        $itemStat->damageTaken_twentyToThirty += $participant->timeline_damageTakenPerMinDeltas_zeroToTen * 10;
        $itemStat->damageTaken_thirtyToEnd += $participant->timeline_damageTakenPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->damageTakenDiff_zeroToTen += $participant->timeline_damageTakenDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->damageTakenDiff_tenToTwenty += $participant->timeline_damageTakenDiffPerMinDeltas_zeroToTen * 10 ;
        $itemStat->damageTakenDiff_twentyToThirty += $participant->timeline_damageTakenDiffPerMinDeltas_zeroToTen * 10;
        $itemStat->damageTakenDiff_thirtyToEnd += $participant->timeline_damageTakenDiffPerMinDeltas_zeroToTen * $match->matchDuration/60 ;

        $itemStat->goldEarned += $participant->stats_goldEarned;
        $itemStat->goldSpent += $participant->stats_goldSpent;
        $itemStat->playerScore += $participant->stats_totalPlayerScore;



        //COUNT CHAMPION BEGIN
        $itemStatChampion = ItemStatChampion::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'championId' => $participant->championId,
        ]);
        $itemStatChampion->champion_count += 1;
        $itemStatChampion->save();
        //COUNT CHAMPION END

        //COUNT LANE BEGIN
        $itemStatChampion = ItemStatLane::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'lane' => $participant->timeline_lane,
        ]);
        $itemStatChampion->lane_count += 1;
        $itemStatChampion->save();
        //COUNT LANE END

        //COUNT ROLE BEGIN
        $itemStatChampion = ItemStatRole::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'role' => $participant->timeline_role,
        ]);
        $itemStatChampion->role_count += 1;
        $itemStatChampion->save();
        //COUNT ROLE END

        //COUNT RANK BEGIN
        $itemStatChampion = ItemStatRank::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'rank' => $participant->highestAchievedSeasonTier,
        ]);
        $itemStatChampion->rank_count += 1;
        $itemStatChampion->save();
        //COUNT RANK END



        //COUNT SPELL1 BEGIN
        $itemStatSpell = ItemStatSpell::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'spellId' => $participant->spell1Id,
        ]);
        $itemStatSpell->spell_count += 1;
        $itemStatSpell->save();
        //COUNT SPELL1 END


        //COUNT SPELL2 BEGIN
        $itemStatSpell = ItemStatSpell::firstOrCreate([
            'item_stat_id' => $itemStat->id,
            'spellId' => $participant->spell2Id,
        ]);
        $itemStatSpell->spell_count += 1;
        $itemStatSpell->save();
        //COUNT SPELL2 END

        $itemStat->last_match_id = $match->id;
        $itemStat->save();
    }


}
