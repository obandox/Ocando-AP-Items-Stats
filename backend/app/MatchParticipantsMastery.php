<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MatchParticipantsMastery extends Model 
{

    protected $table = 'matchs_participants_masteries';

    protected $fillable = [
            'match_participant_id',
            'masteryId',
            'rank',
    ];



    protected $casts = [
        'id' => 'integer', 
    ];
}
