<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPlayer extends Model
{
    protected $fillable = [
        'match_id',
        'player_id',
        'team_id',
    ];

    public function match()
    {
        return $this->belongsTo(Matche::class, 'match_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }   

    public function getPlayerMatchCount(int $playerId)
    {
        return $this->where('player_id', $playerId)->count();
    }
}
