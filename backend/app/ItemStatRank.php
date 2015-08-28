<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatRank extends Model 
{

    protected $table = 'items_stats_ranks';

    protected $fillable = [
        'item_stat_id',
        'rank',
        'rank_count',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}