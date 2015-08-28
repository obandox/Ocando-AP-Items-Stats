<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class ItemStatSpell extends Model 
{

    protected $table = 'items_stats_spells';

    protected $fillable = [
        'item_stat_id',
        'spellId',
        'spell_count',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}