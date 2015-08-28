<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MatchTeam extends Model 
{

    protected $table = 'matchs_teams';

    protected $fillable = [
        'match_id',
        'teamId',
        'winner',
        'firstBlood',
        'firstTower',
        'firstInhibitor',
        'firstBaron',
        'firstDragon',
        'towerKills',
        'inhibitorKills',
        'baronKills',
        'dragonKills',
        'vilemawKills',
        'dominionVictoryScore',
    ];



    protected $casts = [
        'id' => 'integer', 
    ];
}
