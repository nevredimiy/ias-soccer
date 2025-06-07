<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlayerSeriesRegistration extends Model
{
    protected $fillable = [
        'series_meta_id',
        'player_id',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }


    public function seriesMeta(): BelongsTo
    {
        return $this->belongsTo(SeriesMeta::class, 'series_meta_id');
    }
}
