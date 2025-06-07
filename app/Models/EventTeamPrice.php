<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTeamPrice extends Model
{
    protected $fillable = [
        'event_id',
        'team_index',
        'price'
    ];
}
