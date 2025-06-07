<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Elooquent\Relations\BelongsTo;

class SeriesPlayer extends Model
{
    protected $fillable = [
        'series_meta_id',
        'player_id',
        'team_id',
        'player_number'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function seriesMeta()
    {
        return $this->belongsTo(SeriesMeta::class, 'series_meta_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
