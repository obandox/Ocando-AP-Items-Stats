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
use Exception;
use Log;

class SeederItemsChampionsCommand extends Command 
{

    protected $signature = 'seeder:itemschampions';

    protected $description = 'seeder items and champions';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {

           $dirpath = dirname(__FILE__)."/../../public/";

           $champions = json_decode( file_get_contents($dirpath.'champions.json'), true);
            foreach ($champions['data'] as $key => $champion) {                
                $champion_model = Champion::firstOrCreate([
                    'championId' => $champion['id']
                ]);
                $champion_model->fill([
                  "key" => $champion["key"],
                  "name" => $champion["name"],
                  "title" => $champion["title"]
                ]);
                $champion_model->save();
            }

            $items = json_decode( file_get_contents($dirpath.'items.json'), true);
            foreach ($items['data'] as $key => $item) {                
                $item_model = Item::firstOrCreate([
                    'itemId' => $item['id']
                ]);
                $cost = (int)((isset($item["cost"]))?$item["cost"]:'0');

                $item_model->fill([
                  "name" => $item["name"],
                  "group" => (isset($item["group"]))?$item["group"]:'None',
                  "cost" => $cost,
                  "description" => $item["description"]
                ]);
                $item_model->save();
                foreach ($item["builtfrom"] as $from_id) {
                  $from_item_model = Item::firstOrCreate([
                    'itemId' => $from_id
                  ]);
                  ItemBuilt::firstOrCreate([
                      'from_item_id' => $from_item_model->id,
                      'to_item_id' => $item_model->id,
                  ]);
                } 

            }


    }

}