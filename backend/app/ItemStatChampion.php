<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatChampion extends Model 
{

    protected $table = 'items_stats_champions';

    protected $fillable = [
        'item_stat_id',
        'championId',
        'champion_count',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}