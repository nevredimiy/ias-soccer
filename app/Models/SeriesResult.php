<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SeriesResult extends Model
{
    const TYPE_TEAM = 'team';
    const TYPE_SOLO = 'solo';

    protected $fillable = [
        'series_meta_id',
        'team_id',
        'player_id',
        'wins',
        'draw',
        'defeat',
        'goals_scored',
        'goals_conceded',
        'goal_difference',
        'points',
        'scores',
    ];

    protected $casts = [
        'wins' => 'integer',
        'draw' => 'integer',
        'defeat' => 'integer',
        'goals_scored' => 'integer',
        'missed_goals' => 'integer',
        'goal_difference' => 'integer',
        'points' => 'integer',
        'scores' => 'integer',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_TEAM,
            self::TYPE_SOLO,
        ];
    }

    public function seriesMeta()
    {
        return $this->belongsTo(SeriesMeta::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}


