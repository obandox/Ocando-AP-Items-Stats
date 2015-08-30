<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use App\Match;
use App\MatchParticipant;
use App\MatchParticipantIdentity;
use App\MatchParticipantsMastery;
use App\MatchTeam;
use App\Champion;
use App\Item;
use App\ItemBuilt;
use App\ItemStat;
use Exception;
use Log;

class StatsItems2Command extends Command 
{
    protected $signature = 'stats:items2';

    protected $description = 'Sync AP ITEMS';

    public function __construct()
    {
        parent::__construct();
    }


    public function ExecQuery($url){
        $key = "13a0d135-286d-4ce4-97c0-cf2cc8015f2e";
        $client = new \GuzzleHttp\Client();
        $realpath = realpath('./ca-bundle.crt');
        $results = $client->get("https://na.api.pvp.net".$url."?api_key=".$key, 
            [
                'verify' => $realpath
            ]);
        $body = $results->getBody();
        $json = json_decode($body,true);
        return  $json;
    }

    public function fire()
    {
        echo "SYNC AP_ITEMS \n";

        $sources = [
            'AP_ITEM_DATASET/5.11/NORMAL_5X5',
            'AP_ITEM_DATASET/5.11/RANKED_SOLO',
            'AP_ITEM_DATASET/5.14/NORMAL_5X5',
            'AP_ITEM_DATASET/5.14/RANKED_SOLO',
        ];


       foreach ($sources as $source) { 
           echo "Source: $source \n";

           $dirpath = dirname(__FILE__)."/../../public/$source/";
          
            if ($handle = opendir($dirpath)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $region = strtolower($entry);
                        $region = str_replace(".json","",$region);
                        $filepath = $dirpath.$entry;
                        $matchIds =  json_decode(file_get_contents($filepath), true);
                        $requests = 0;
                        foreach ($matchIds as $matchId) {
                            echo "$region match: $matchId request: $requests \n";

                            $requests++;

                            $match_model = Match::where([
                                'source' => $source,
                                'matchId' => $matchId,
                                'region' => strtoupper($region),
                            ])->first();


                            if($match_model){
                                echo "already exists.. skip \n";
                                continue;
                            }


                            try{
                                $this->registerMatch($source, $region, $matchId);
                                echo "waiting...\n";
                                sleep(1.3);
                            }catch(Exception $unused){
                                echo "error... \n";
                                Log::error($unused);
                            }

                        }

                    }

                }
                closedir($handle);
            }
       }



    }


    public function process($match, $participant){
        if($participant->stats_item0){
            //echo "$region itemId: ".$participant->stats_item0." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
            ItemStat::calc($match, $participant, $participant->stats_item0);
        }
        
        if($participant->stats_item1){
          //echo "$region itemId: ".$participant->stats_item1." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item1);
        }
        
        if($participant->stats_item2){
          //echo "$region itemId: ".$participant->stats_item2." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item2);
        }
        
        if($participant->stats_item3){
          //echo "$region itemId: ".$participant->stats_item3." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item3);
        }
        
        if($participant->stats_item4){
          //echo "$region itemId: ".$participant->stats_item4." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item4);
        }
        
        if($participant->stats_item5){
          //echo "$region itemId: ".$participant->stats_item5." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item5);
        }
        
        if($participant->stats_item6){
          //echo "$region itemId: ".$participant->stats_item6." matchId: ".$match->matchId." participantId: ".$participant->participantId."\n";
          ItemStat::calc($match, $participant, $participant->stats_item6);                  
        }
    }

    public function registerMatch($source, $region,$matchId){

        $match = $this->ExecQuery("/api/lol/".$region."/v2.2/match/".$matchId);


        $match_model = Match::firstOrCreate([
            'source' => $source,
            'matchId' => $match['matchId'],
            'region' => $match['region'],
        ]);
        $match_model->fill([
            'platformId' => $match['platformId'],
            'matchMode' => $match['matchMode'],
            'matchType' => $match['matchType'],
            'matchCreation' => $match['matchCreation'],
            'matchDuration' => $match['matchDuration'],
            'queueType' => $match['queueType'],
            'mapId' => $match['mapId'],
            'season' => $match['season'],
            'matchVersion' => $match['matchVersion'],
        ]);


        foreach ($match['participants'] as $participant) {

                $participant_model = MatchParticipant::firstOrCreate([
                    'match_id' => $match_model->id,
                    'teamId' => $participant['teamId'],
                    'participantId' => $participant['participantId'],
                ]);
                $participant_model->fill([
                    'spell1Id' => $participant['spell1Id'],
                    'spell2Id' => $participant['spell2Id'],
                    'championId' => $participant['championId'],
                    'highestAchievedSeasonTier' => $participant['highestAchievedSeasonTier'],
                    'stats_winner' => $participant['stats']['winner'],
                    'stats_champLevel' => $participant['stats']['champLevel'],
                    'stats_item0' => $participant['stats']['item0'],
                    'stats_item1' => $participant['stats']['item1'],
                    'stats_item2' => $participant['stats']['item2'],
                    'stats_item3' => $participant['stats']['item3'],
                    'stats_item4' => $participant['stats']['item4'],
                    'stats_item5' => $participant['stats']['item5'],
                    'stats_item6' => $participant['stats']['item6'],
                    'stats_kills' => $participant['stats']['kills'],
                    'stats_deaths' => $participant['stats']['deaths'],
                    'stats_assists' => $participant['stats']['assists'],
                    'stats_doubleKills' => $participant['stats']['doubleKills'],
                    'stats_tripleKills' => $participant['stats']['tripleKills'],
                    'stats_quadraKills' => $participant['stats']['quadraKills'],
                    'stats_pentaKills' => $participant['stats']['pentaKills'],
                    'stats_unrealKills' => $participant['stats']['unrealKills'],
                    'stats_largestKillingSpree' => $participant['stats']['largestKillingSpree'],
                    'stats_totalDamageDealt' => $participant['stats']['totalDamageDealt'],
                    'stats_totalDamageDealtToChampions' => $participant['stats']['totalDamageDealtToChampions'],
                    'stats_totalDamageTaken' => $participant['stats']['totalDamageTaken'],
                    'stats_largestCriticalStrike' => $participant['stats']['largestCriticalStrike'],
                    'stats_totalHeal' => $participant['stats']['totalHeal'],
                    'stats_minionsKilled' => $participant['stats']['minionsKilled'],
                    'stats_neutralMinionsKilled' => $participant['stats']['neutralMinionsKilled'],
                    'stats_neutralMinionsKilledTeamJungle' => $participant['stats']['neutralMinionsKilledTeamJungle'],
                    'stats_neutralMinionsKilledEnemyJungle' => $participant['stats']['neutralMinionsKilledEnemyJungle'],
                    'stats_goldEarned' => $participant['stats']['goldEarned'],
                    'stats_goldSpent' => $participant['stats']['goldSpent'],
                    'stats_combatPlayerScore' => $participant['stats']['combatPlayerScore'],
                    'stats_objectivePlayerScore' => $participant['stats']['objectivePlayerScore'],
                    'stats_totalPlayerScore' => $participant['stats']['totalPlayerScore'],
                    'stats_totalScoreRank' => $participant['stats']['totalScoreRank'],
                    'stats_magicDamageDealtToChampions' => $participant['stats']['magicDamageDealtToChampions'],
                    'stats_physicalDamageDealtToChampions' => $participant['stats']['physicalDamageDealtToChampions'],
                    'stats_trueDamageDealtToChampions' => $participant['stats']['trueDamageDealtToChampions'],
                    'stats_visionWardsBoughtInGame' => $participant['stats']['visionWardsBoughtInGame'],
                    'stats_sightWardsBoughtInGame' => $participant['stats']['sightWardsBoughtInGame'],
                    'stats_magicDamageDealt' => $participant['stats']['magicDamageDealt'],
                    'stats_physicalDamageDealt' => $participant['stats']['physicalDamageDealt'],
                    'stats_trueDamageDealt' => $participant['stats']['trueDamageDealt'],
                    'stats_magicDamageTaken' => $participant['stats']['magicDamageTaken'],
                    'stats_physicalDamageTaken' => $participant['stats']['physicalDamageTaken'],
                    'stats_trueDamageTaken' => $participant['stats']['trueDamageTaken'],
                    'stats_firstBloodKill' => $participant['stats']['firstBloodKill'],
                    'stats_firstBloodAssist' => $participant['stats']['firstBloodAssist'],
                    'stats_firstTowerKill' => $participant['stats']['firstTowerKill'],
                    'stats_firstTowerAssist' => $participant['stats']['firstTowerAssist'],
                    'stats_firstInhibitorKill' => $participant['stats']['firstInhibitorKill'],
                    'stats_firstInhibitorAssist' => $participant['stats']['firstInhibitorAssist'],
                    'stats_inhibitorKills' => $participant['stats']['inhibitorKills'],
                    'stats_towerKills' => $participant['stats']['towerKills'],
                    'stats_wardsPlaced' => $participant['stats']['wardsPlaced'],
                    'stats_wardsKilled' => $participant['stats']['wardsKilled'],
                    'stats_largestMultiKill' => $participant['stats']['largestMultiKill'],
                    'stats_killingSprees' => $participant['stats']['killingSprees'],
                    'stats_totalUnitsHealed' => $participant['stats']['totalUnitsHealed'],
                    'stats_totalTimeCrowdControlDealt' => $participant['stats']['totalTimeCrowdControlDealt'],    
                    'timeline_role' => $participant['timeline']['role'],
                    'timeline_lane' => $participant['timeline']['lane'],
                ]);
                
                
                if(isset($participant['timeline']['creepsPerMinDeltas'])){
                    $local = $participant['timeline']['creepsPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_creepsPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_creepsPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_creepsPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_creepsPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }

                if(isset($participant['timeline']['xpPerMinDeltas'])){
                    $local = $participant['timeline']['xpPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_xpPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_xpPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_xpPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_xpPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }

                
                if(isset($participant['timeline']['goldPerMinDeltas'])){
                    $local = $participant['timeline']['goldPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_goldPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_goldPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_goldPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_goldPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }


                if(isset($participant['timeline']['damageTakenPerMinDeltas'])){
                    $local = $participant['timeline']['damageTakenPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_damageTakenPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_damageTakenPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_damageTakenPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_damageTakenPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }


                if(isset($participant['timeline']['damageTakenDiffPerMinDeltas'])){
                    $local = $participant['timeline']['damageTakenDiffPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_damageTakenDiffPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_damageTakenDiffPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_damageTakenDiffPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_damageTakenDiffPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }

                if(isset($participant['timeline']['xpDiffPerMinDeltas'])){
                    $local = $participant['timeline']['xpDiffPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_xpDiffPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_xpDiffPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_xpDiffPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_xpDiffPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }

                if(isset($participant['timeline']['csDiffPerMinDeltas'])){
                    $local = $participant['timeline']['csDiffPerMinDeltas'];
                    $participant_model->fill([
                        'timeline_csDiffPerMinDeltas_zeroToTen' => (isset($local['zeroToTen']))?$local['zeroToTen']:0 ,
                        'timeline_csDiffPerMinDeltas_tenToTwenty' => (isset($local['tenToTwenty']))?$local['tenToTwenty']:0,
                        'timeline_csDiffPerMinDeltas_twentyToThirty' => (isset($local['twentyToThirty']))?$local['twentyToThirty']:0,
                        'timeline_csDiffPerMinDeltas_thirtyToEnd' => (isset($local['thirtyToEnd']))?$local['thirtyToEnd']:0,
                    ]);
                }

                $this->process($match_model, $participant_model);
        }


    }

    protected function getArguments()
    {
        return array(
          //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }


    protected function getOptions()
    {
        return array(
          //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.' null),
        );
    }


}