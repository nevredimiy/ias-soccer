<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeriesTeam extends Model
{
    protected $fillable = [
        'series_meta_id',
        'team_id',
        'status'
    ];

     /**
     * Отношение к серии (meta).
     */
    public function seriesMeta(): BelongsTo
    {
        return $this->belongsTo(SeriesMeta::class);
    }

    /**
     * Отношение к команде.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
