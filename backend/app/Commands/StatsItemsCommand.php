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

class StatsItemsCommand extends Command 
{

    protected $signature = 'stats:items';

    protected $description = 'calc stats items';

    public function __construct()
    {
        parent::__construct();
    }

    public function process($source, $region){
        $itemStat = ItemStat::where([
            'source' => $source,
            'region' => $region,
        ])->first();

        $matchs = Match::orderBy('id', 'ASC')
        ->whereSource($source)
        ->whereRegion($region);


        $matchs->chunk(200, function($matchs) use($source, $region, $itemStat) {

            foreach ($matchs as $match) {
                

                if($itemStat && $itemStat->last_match_id && $itemStat->last_match_id > $match->id){
                    //echo $itemStat->match_id." skip ".$match->matchId."... \n";
                    continue;                
                }else{
                    //echo "Process: ".$match->id." matchId: ".$match->matchId." \n";
                }

                foreach ($match->participants as $participant) {
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
            }
        });

    }

    public function fire()
    {
        echo "STATS ALL ITEMS \n";

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
                        $region = strtoupper($region);
                        echo "REGION: $region \n";
                        $this->process($source, $region)." \n";

                    }
                }
                closedir($handle);
            }
        }

    }


}