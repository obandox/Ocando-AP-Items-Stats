<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatLane extends Model 
{

    protected $table = 'items_stats_lanes';

    protected $fillable = [
        'item_stat_id',
        'lane',
        'lane_count',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}