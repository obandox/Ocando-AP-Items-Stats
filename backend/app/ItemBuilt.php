<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemBuilt extends Model 
{

    protected $table = 'items_builts';

    protected $fillable = [
        'from_item_id',
        'to_item_id',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}