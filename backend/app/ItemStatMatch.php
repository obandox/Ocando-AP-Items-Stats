<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatMatch extends Model 
{

    protected $table = 'items_stats_matchs';

    protected $fillable = [
        'item_stat_id',
        'matchId',
        'match_count',
        'win',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}