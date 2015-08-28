<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Champion extends Model 
{

    protected $table = 'champions';

    protected $fillable = [
        'championId',
        'name',
        'key',
        'title',            
    ];

    protected $casts = [
        'id' => 'integer', 
    ];
}
