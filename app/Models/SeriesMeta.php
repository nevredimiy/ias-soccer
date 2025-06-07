<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Elooquent\Relations\HasMany;

class SeriesMeta extends Model
{
    protected $fillable = [
        'event_id',
        'stadium_id',
        'league_id',
        'size_field',
        'date',
        'start_date',
        'end_date',
        'series',
        'round',
        'price',
        'status_registration',


    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function players()
    {
        return $this->belongsToMany(Player::class, 'series_players', 'series_meta_id', 'player_id');
    }
    public function stadium()
    {
        return $this->belongsTo(Stadium::class, 'stadium_id');
    }

    public function seriesResults()
    {
        return $this->hasMany(SeriesResult::class, 'series_meta_id');
    }

    // public function matchesEvents()
    // {
    //     return $this->hasMany(MatcheEvent::class, 'series_meta_id');
    // }

   
    public function teams()
    {
        return $this->hasManyThrough(
            Team::class,
            Event::class,
            'id',         // Foreign key on the events table...
            'event_id',   // Foreign key on the teams table...
            'event_id',   // Local key on the series_metas table...
            'id'          // Local key on the events table...
        );
    }

    public function seriesTeams()
    {
        return $this->hasMany(SeriesTeam::class, 'series_meta_id');
    }

    

    public function seriesPlayers()
    {
        return $this->hasMany(SeriesPlayer::class, 'series_meta_id');
    }

    public function matches()
    {
        return $this->hasMany(Matche::class, 'series_meta_id');
    }

    public function playerSeriesRegistration()
    {
        return $this->hasMany(PlayerSeriesRegistration::class, 'series_meta_id');
    }

    public function voters()
    {
        return $this->hasMany(Voter::class, 'series_meta_id');
    }

    public function getStartDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i');
    }
    public function getEndDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i');
    }
    public function getDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i');
    }
    
}
