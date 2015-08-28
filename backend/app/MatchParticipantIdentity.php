<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MatchParticipantIdentity extends Model 
{

    protected $table = 'matchs_participantIdentities';

    protected $fillable = [
        'match_id',
        'participantId',
    ];



    protected $casts = [
        'id' => 'integer', 
    ];
}
