<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class Item extends Model 
{

    protected $table = 'items';

    protected $fillable = [
        'itemId',
        'name',
        'group',
        'cost',
        'description',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];

    public static $_source = "";
    public static function listInMatchs($region, $source, $type){
        self::$_source = $source;
        //Ability Power
        $app_url = Config::get('app.url');

        $items = DB::table('items as i');

        if($type){
            $type = strtolower($type);
            $desc = false;

            $types = [
                'Gold',
                'Armor',
                'Health',
                'Mana',
                'Magic Resist',
                'Ability Power',
                'Attack damage',
                'Attack Speed',
                'Cooldown Reduction',
                'Movement',
                'Critical Strike',
            ];


            foreach ($types as $local_type) {
                $lower_type = strtolower($local_type);
                $url_type = str_replace(" ", "", $url_type);

                if($url_type == $type){
                    $desc = $lower_type;
                }

            }



            if($desc)
                $items = $items->where(function($query) use($desc){
                    $query->whereRaw('lower(i.description) like \'%'.$desc.'%\'');
                });
        }else{

            $items = $items->whereIn('i.itemId', [
                //'1052',//amplifying
                '3152',//ancients
                '3003',//archangel
                '3174',//athene
                //'1026',//blastingwand
                //'3136',//haunting
                //'1058',//largerod
                '3151',//liandry
                '3285',//ludens
                '3165',//morello
                '3115',//nashor
                '3089',//rabadon
                '3027',//rodofages
                '3116',//rylai
                //'3040',//seraph
                '3135',//voidstaff
                '3157',//zonhyas
            ]);
        }

        $items = $items->join('items_stats as s', function($join) use($region, $source) {
            $join->on('s.item_id','=','i.id')            
            ->where('s.region','=',$region)
            ->where('s.source','=', $source);
        })
        ->groupBy('i.itemId')
        ->select([
            'i.id', 
            'i.itemId', 
            's.id as item_stat_id',  
            'i.name',           
            'i.group',           
            'i.description',           
            'i.cost',
            DB::raw("concat('$app_url/items/64x64/',itemId,'.png') as image64x64_url"),
            DB::raw("round( ifnull(sum(s.timesused),1)  ) as timesused"),

            's.wins',    
            's.loses',    
            's.games',

            DB::raw("round( ifnull(sum(s.wins),0) / ifnull(sum(s.games),1) * 100 ) as win_rate"),

            DB::raw("round( ifnull(sum(s.matchDuration),0) / ifnull(sum(s.games),1) ) as avg_matchDuration"),

            DB::raw("round( ifnull(sum(s.kills),0) / ifnull(sum(s.timesused),1) ) as avg_kills"),
            DB::raw("round( ifnull(sum(s.deaths),0) / ifnull(sum(s.timesused),1) ) as avg_deaths"),
            DB::raw("round( ifnull(sum(s.assists),0) / ifnull(sum(s.timesused),1) ) as avg_assists"),

            DB::raw("round( ifnull(sum(s.pentaKills),0) / ifnull(sum(s.timesused),1) ) as avg_pentaKills"),
            DB::raw("round( ifnull(sum(s.magicDamageDealtToChampions),0) / ifnull(sum(s.timesused),1) ) as avg_magicDamageDealtToChampions"),
            DB::raw("round( ifnull(sum(s.minionsKilled),0) / ifnull(sum(s.timesused),1) ) as avg_minionsKilled"),
            DB::raw("round( ifnull(sum(s.creeps_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_creeps_zeroToTen"),
            DB::raw("round( ifnull(sum(s.creeps_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_creeps_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.creeps_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_creeps_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.creeps_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_creeps_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.xp_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_xp_zeroToTen"),
            DB::raw("round( ifnull(sum(s.xp_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_xp_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.xp_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_xp_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.xp_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_xp_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.gold_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_gold_zeroToTen"),
            DB::raw("round( ifnull(sum(s.gold_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_gold_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.gold_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_gold_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.gold_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_gold_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.csDiff_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_csDiff_zeroToTen"),
            DB::raw("round( ifnull(sum(s.csDiff_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_csDiff_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.csDiff_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_csDiff_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.csDiff_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_csDiff_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.xpDiff_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_xpDiff_zeroToTen"),
            DB::raw("round( ifnull(sum(s.xpDiff_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_xpDiff_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.xpDiff_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_xpDiff_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.xpDiff_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_xpDiff_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.damageTaken_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_damageTaken_zeroToTen"),
            DB::raw("round( ifnull(sum(s.damageTaken_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_damageTaken_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.damageTaken_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_damageTaken_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.damageTaken_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_damageTaken_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.damageTakenDiff_zeroToTen),0) / ifnull(sum(s.timesused),1) ) as avg_damageTakenDiff_zeroToTen"),
            DB::raw("round( ifnull(sum(s.damageTakenDiff_tenToTwenty),0) / ifnull(sum(s.timesused),1) ) as avg_damageTakenDiff_tenToTwenty"),
            DB::raw("round( ifnull(sum(s.damageTakenDiff_twentyToThirty),0) / ifnull(sum(s.timesused),1) ) as avg_damageTakenDiff_twentyToThirty"),
            DB::raw("round( ifnull(sum(s.damageTakenDiff_thirtyToEnd),0) / ifnull(sum(s.timesused),1) ) as avg_damageTakenDiff_thirtyToEnd"),
            DB::raw("round( ifnull(sum(s.goldEarned),0) / ifnull(sum(s.timesused),1) ) as avg_goldEarned"),
            DB::raw("round( ifnull(sum(s.goldSpent),0) / ifnull(sum(s.timesused),1) ) as avg_goldSpent"),
            DB::raw("round( ifnull(sum(s.playerScore),0) / ifnull(sum(s.timesused),1) ) as avg_playerScore"),

        ]);


        $items = $items->orderBy('timesused', 'DESC')->get();
        $result = [];
        foreach ($items as $item) {
            $item->{'cost'} = self::checkCosts($item->{'cost'}, $item->{'itemId'});
            $builtfrom = self::builtfrom($item->id);
            $totalCost = 0;
            for ($i=0; $i < count($builtfrom); $i++) { 
                $totalCost +=  $builtfrom[$i]->cost ;
            }
            $diffCost =  abs($item->{'cost'} - $totalCost);

            $ret = [
                'itemId' => $item->{'itemId'},
                'name' => $item->{'name'},
                'group' => $item->{'group'},
                'description' => $item->{'description'},
                'cost' => $item->{'cost'},
                'diffCost' => $diffCost,
                'image64x64_url' => $item->{'image64x64_url'},
                'timesused' => (int) $item->{'timesused'},
                'wins' => (int) $item->{'wins'} ,
                'loses' => (int) $item->{'loses'} ,
                'games' => (int) $item->{'games'} ,
                'win_rate' => (int) $item->{'win_rate'} ,
                'avg_matchDuration' => (int) $item->{'avg_matchDuration'}, 
                'avg_kills' => (int) $item->{'avg_kills'} ,
                'avg_deaths' => (int) $item->{'avg_deaths'} ,
                'avg_assists' => (int) $item->{'avg_assists'} ,
                'avg_pentaKills' => (int) $item->{'avg_pentaKills'} ,
                'avg_magicDamageDealtToChampions' => (int) $item->{'avg_magicDamageDealtToChampions'} ,
                'avg_minionsKilled' => (int) $item->{'avg_minionsKilled'} ,
                'avg_creeps_zeroToTen' => (int) $item->{'avg_creeps_zeroToTen'} ,
                'avg_creeps_tenToTwenty' => (int) $item->{'avg_creeps_tenToTwenty'} ,
                'avg_creeps_twentyToThirty' => (int) $item->{'avg_creeps_twentyToThirty'} ,
                'avg_creeps_thirtyToEnd' => (int) $item->{'avg_creeps_thirtyToEnd'} ,
                'avg_xp_zeroToTen' => (int) $item->{'avg_xp_zeroToTen'} ,
                'avg_xp_tenToTwenty' => (int) $item->{'avg_xp_tenToTwenty'} ,
                'avg_xp_twentyToThirty' => (int) $item->{'avg_xp_twentyToThirty'} ,
                'avg_xp_thirtyToEnd' => (int) $item->{'avg_xp_thirtyToEnd'} ,
                'avg_gold_zeroToTen' => (int) $item->{'avg_gold_zeroToTen'} ,
                'avg_gold_tenToTwenty' => (int) $item->{'avg_gold_tenToTwenty'} ,
                'avg_gold_twentyToThirty' => (int) $item->{'avg_gold_twentyToThirty'} ,
                'avg_gold_thirtyToEnd' => (int) $item->{'avg_gold_thirtyToEnd'} ,
                'avg_csDiff_zeroToTen' => (int) $item->{'avg_csDiff_zeroToTen'} ,
                'avg_csDiff_tenToTwenty' => (int) $item->{'avg_csDiff_tenToTwenty'} ,
                'avg_csDiff_twentyToThirty' => (int) $item->{'avg_csDiff_twentyToThirty'} ,
                'avg_csDiff_thirtyToEnd' => (int) $item->{'avg_csDiff_thirtyToEnd'} ,
                'avg_xpDiff_zeroToTen' => (int) $item->{'avg_xpDiff_zeroToTen'} ,
                'avg_xpDiff_tenToTwenty' => (int) $item->{'avg_xpDiff_tenToTwenty'} ,
                'avg_xpDiff_twentyToThirty' => (int) $item->{'avg_xpDiff_twentyToThirty'} ,
                'avg_xpDiff_thirtyToEnd' => (int) $item->{'avg_xpDiff_thirtyToEnd'} ,
                'avg_damageTaken_zeroToTen' => (int) $item->{'avg_damageTaken_zeroToTen'} ,
                'avg_damageTaken_tenToTwenty' => (int) $item->{'avg_damageTaken_tenToTwenty'} ,
                'avg_damageTaken_twentyToThirty' => (int) $item->{'avg_damageTaken_twentyToThirty'} ,
                'avg_damageTaken_thirtyToEnd' => (int) $item->{'avg_damageTaken_thirtyToEnd'} ,
                'avg_damageTakenDiff_zeroToTen' => (int) $item->{'avg_damageTakenDiff_zeroToTen'} ,
                'avg_damageTakenDiff_tenToTwenty' => (int) $item->{'avg_damageTakenDiff_tenToTwenty'} ,
                'avg_damageTakenDiff_twentyToThirty' => (int) $item->{'avg_damageTakenDiff_twentyToThirty'} ,
                'avg_damageTakenDiff_thirtyToEnd' => (int) $item->{'avg_damageTakenDiff_thirtyToEnd'} ,
                'avg_goldEarned' => (int) $item->{'avg_goldEarned'} ,
                'avg_goldSpent' => (int) $item->{'avg_goldSpent'} ,
                'avg_playerScore' => (int) $item->{'avg_playerScore'} ,
            ];



            $ret['builtfrom'] = $builtfrom;
            $ret['champions'] = self::champions($item->item_stat_id);
            $ret['lanes'] = self::lanes($item->item_stat_id);
            $ret['ranks'] = self::ranks($item->item_stat_id);
            $ret['roles'] = self::roles($item->item_stat_id);
            $ret['spell'] = self::spell($item->item_stat_id);
            
            $result [] = $ret;
        }


        return $result;
    }

    public static $_itemCache = [];
    public static function getItem($itemId){
        if(!isset(self::$_itemCache[$itemId])){
            self::$_itemCache[$itemId] = Item::where(['id'=>$itemId])->first();
        }
        return self::$_itemCache[$itemId];
    }



    public static function checkCosts($cost, $itemId){
        $old_costs = [
            '3285' => '3300',
            '3089'  => '3300',
            '3157' => '3300',
            '3135' => '2295',
            '3027' => '2800',
            '3151' => '2900',
            '3116' => '2900',
            '3115' => '2920',
            '3003' => '2700',
            '1058' => '1600',
            '1026' => '860',
            '3136' => '1480',
        ];
        if(strpos(self::$_source, '5.11') !== false){
           
            if(isset($old_costs[$itemId])){
                $cost = $old_costs[$itemId];
            }
        }
        return $cost;
    }

    public static function builtfrom($item_id){
        $app_url = Config::get('app.url');
        $models = [];

        $old_build = [
            '3089' => [
                '1026',
                '1058',
            ],
            '3116' =>[
                '1026',
                '1052',
                '1011',
            ],
            '3003' =>[
                '1026',
                '3070',
            ],
            '3151' => [
                '1052',
                '3136',
            ],
            '3174' => [
                '3108',
                '3028',
            ],
            '3165' => [
                '3108',
                '3114',
            ],
        ];
        if(strpos(self::$_source, '5.11') !== false){
            $item = self::getItem($item_id);
            $itemId = $item->itemId;
            if(isset($old_build[$itemId])){
                $items = DB::table('items as i')
                ->whereIn('i.itemId', $old_build[$itemId] )
                ->select([
                    'i.itemId',   
                    'i.name',           
                    'i.group',           
                    'i.description',           
                    'i.cost',
                    DB::raw("concat('$app_url/items/64x64/',i.itemId,'.png') as image64x64_url"),
                ]);
                $models = $items->get();
            }
        }

        if(count($models) == 0){
            $items = DB::table('items_builts as b')
            ->join('items as i', 'b.from_item_id', '=', 'i.id')
            ->where('b.to_item_id','=',$item_id)
            ->select([
                'i.itemId',   
                'i.name',           
                'i.group',           
                'i.description',           
                'i.cost',
                DB::raw("concat('$app_url/items/64x64/',itemId,'.png') as image64x64_url"),
            ]);
            $models = $items->get();
        }

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->{'cost'} = self::checkCosts($models[$i]->{'cost'}, $models[$i]->{'itemId'});

            $models[$i]->text = strip_tags( $models[$i]->description );
        }
         
        return $models;
    }

    public static function champions($item_stat_id){
        $app_url = Config::get('app.url');
        $builder = DB::table('items_stats_champions as s')
            ->join('champions as c', 's.championId', '=', 'c.championId')
            ->where('s.item_stat_id','=',$item_stat_id)
            ->select([

                'c.championId',
                'c.name',
                'c.key',
                'c.title',
                's.champion_count',
                 DB::raw("concat('$app_url/champions/199x362/',c.championId,'.jpg') as image199x362_url"),
                 DB::raw("concat('$app_url/champions/1215x717/',c.championId,'.jpg') as image1215x717_url"),
                
            ]);
        
        $models =  $builder->orderBy('champion_count', 'DESC')->take(5)->get();

        $total = 0;
        for ($i=0; $i < count($models); $i++) { 
            $total+=$models[$i]->champion_count;
        }
        if(!$total) $total = 1;

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->champion_rate =(int) ($models[$i]->champion_count / $total * 100);
        }

        return $models;
    }

    public static function lanes($item_stat_id){
        $app_url = Config::get('app.url');
        $builder = DB::table('items_stats_lanes as s')
            ->where('s.item_stat_id','=',$item_stat_id)
            ->select([
                's.lane as name',
                's.lane',     
                's.lane_count',           
            ]);
        
        $models =  $builder->orderBy('lane_count', 'DESC')->take(2)->get();

        $total = 0;
        for ($i=0; $i < count($models); $i++) { 
            $total+=$models[$i]->lane_count;
        }
        if(!$total) $total = 1;

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->name = ucfirst( strtolower($models[$i]->name) );
            $models[$i]->lane_rate =(int) ($models[$i]->lane_count / $total * 100);
        }

        return $models;
    }

    public static function ranks($item_stat_id){
        $app_url = Config::get('app.url');
        $builder = DB::table('items_stats_ranks as s')
            ->where('s.item_stat_id','=',$item_stat_id)
            ->whereNotIn('s.rank',[
                'UNRANKED'
            ])
            ->select([
                's.rank as name',
                's.rank',    
                's.rank_count', 
                 DB::raw("concat('$app_url/rank/',s.rank,'.png') as image_url"),  
            ]);
        $models = $builder->orderBy('rank_count', 'DESC')->take(3)->get();

        $total = 0;
        for ($i=0; $i < count($models); $i++) { 
            $total+=$models[$i]->rank_count;
        }
        if(!$total) $total = 1;

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->name = ucfirst( strtolower($models[$i]->name) );
            $models[$i]->rank_rate = (int) ($models[$i]->rank_count / $total * 100);
        }

        return $models;

    }
    public static function roles($item_stat_id){
        $app_url = Config::get('app.url');
        $builder = DB::table('items_stats_roles as s')
            ->where('s.item_stat_id','=',$item_stat_id)
            ->select([
                's.role as name',
                's.role',    
                's.role_count',            
            ]);
        
        $models = $builder->orderBy('role_count', 'DESC')->take(3)->get();

        $total = 0;
        for ($i=0; $i < count($models); $i++) { 
            $total+=$models[$i]->role_count;
        }
        if(!$total) $total = 1;

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->name = ucfirst( strtolower($models[$i]->name) );
            $models[$i]->role_rate = (int) ($models[$i]->role_count / $total * 100);
        }

        return $models;

    }
    public static function spell($item_stat_id){
        $app_url = Config::get('app.url');

        $spells = [
            '1' => [
                'spellId' => '1',
                'name' => 'Cleanse'
            ],
            '2' => [
                'spellId' => '2',
                'name' => 'Clairvoyance'
            ],
            '3' => [
                'spellId' => '3',
                'name' => 'Exhaust'
            ],
            '4' => [
                'spellId' => '4',
                'name' => 'Flash'
            ],
            '6' => [
                'spellId' => '6',
                'name' => 'Ghost'
            ],
            '7' => [
                'spellId' => '7',
                'name' => 'Heal'
            ],
            '11' => [
                'spellId' => '11',
                'name' => 'Smite'
            ],
            '12' => [
                'spellId' => '12',
                'name' => 'Teleport'
            ],
            '13' => [
                'spellId' => '13',
                'name' => 'Clarity'
            ],
            '14' => [
                'spellId' => '14',
                'name' => 'Ignite'
            ],
            '17' => [
                'spellId' => '17',
                'name' => 'Garrison'
            ],
            '21' => [
                'spellId' => '21',
                'name' => 'Barrier'
            ],
            '30' => [
                'spellId' => '30',
                'name' => 'Poro King'
            ],
            '31' => [
                'spellId' => '31',
                'name' => 'Poro Toss'
            ],
            '32' => [
                'spellId' => '32',
                'name' => 'Mark'
            ],
        ];

        $except = [
            '3',
            '4',
            '7',
            '12',
            '14',
        ];

        $builder = DB::table('items_stats_spells as s')
            ->where('s.item_stat_id','=',$item_stat_id)
            ->whereNotIn('s.spellId', $except)
            ->select([
                's.spellId',    
                's.spell_count',         
                 DB::raw("concat('$app_url/spells/64x64/',s.spellId,'.png') as image64x64_url"),   
            ]);
        $models = $builder->orderBy('spell_count', 'DESC')->take(3)->get();

        $total = 0;
        for ($i=0; $i < count($models); $i++) { 
            $total+=$models[$i]->spell_count;
        }
        if(!$total) $total = 1;

        for ($i=0; $i < count($models); $i++) { 
            $models[$i]->spell_rate = (int) ($models[$i]->spell_count / $total * 100);
            $models[$i]->name = $spells[$models[$i]->spellId]["name"];
            $models[$i]->spell = strtoupper( $models[$i]->name );
        }

        return $models;

    }


}
