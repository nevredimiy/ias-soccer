<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'tournament_id',
        'format',
        'price',
        'access_code',
        'status'
    ];


    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'event_id');
    }

    public function matches()
    {
        return $this->hasMany(Matche::class, 'event_id');
    }

    public function stadiums()
    {
        return $this->seriesMeta()->with('stadium')->first();
    }

    
    public function seriesMeta(): HasMany
    {
        return $this->hasMany(SeriesMeta::class, 'event_id');
    }

    

}
