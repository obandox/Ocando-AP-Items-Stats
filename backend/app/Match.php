<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Match extends Model 
{

    protected $table = 'matchs';

    protected $fillable = [
            'source',
            'matchId',
            'region',
            'platformId',
            'matchMode',
            'matchType',
            'matchCreation',
            'matchDuration',
            'queueType',
            'mapId',
            'season',
            'matchVersion',
    ];

    protected $casts = [
        'id' => 'integer', 
    ];


    public function participants()
    {
        return $this->hasMany('App\MatchParticipant','match_id');
    }

}
