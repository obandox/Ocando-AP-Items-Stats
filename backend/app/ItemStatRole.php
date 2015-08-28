<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatRole extends Model 
{

    protected $table = 'items_stats_roles';

    protected $fillable = [
        'item_stat_id',
        'role',
        'role_count',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}