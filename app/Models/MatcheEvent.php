<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatcheEvent extends Model
{
    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'type',
        'minute',
        'assister_id',
        'comment'
    ];

    public function match()
    {
        return $this->belongsTo(Matche::class, 'match_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
    public function assister()
    {
        return $this->belongsTo(Player::class, 'assister_id');
    }
    
    public function getMinuteAttribute($value)
    {
        return $value . ' мин';
    }
    public function getCommentAttribute($value)
    {
        return $value ?? 'Нет комментария';
    }
    
}
