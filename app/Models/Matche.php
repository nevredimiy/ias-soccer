<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matche extends Model
{
    protected $fillable = [
        'event_id',
        'series_meta_id',
        'team1_id',
        'team2_id',
        'start_time',
        'series',
        'round',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_teams', 'player_id', 'team_id')
                    ->withTimestamps();
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_teams', 'team_id', 'player_id')
                    ->withTimestamps();
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function seriesMeta()
    {
        return $this->belongsTo(SeriesMeta::class, 'series_meta_id');
    }

    public function matchEvents()
    {
        return $this->hasMany(MatcheEvent::class, 'match_id');
    }

    public function goalsTeam1()
    {
        return $this->hasMany(MatcheEvent::class, 'match_id')
            ->where('type', 'goal')
            ->whereColumn('team_id', 'team1_id');
    }

    public function goalsTeam2()
    {
        return $this->hasMany(MatcheEvent::class, 'match_id')
            ->where('type', 'goal')
            ->whereColumn('team_id', 'team2_id');
    }


    
   
}
