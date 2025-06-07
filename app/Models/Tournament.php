<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $table = 'tournaments';
    protected $fillable = [
        'name', 
        'type', 
        'subtype', 
        'sort_order',
        'count_teams',
        'count_rounds',
        'count_series',
        'count_matches',
        'team_creator',
    ];
    protected $casts = [
        'sort_order' => 'integer',
    ];
    protected $attributes = [
        'type' => 'team',
        'subtype' => 'regular',
    ];  

    public function events()
    {
        return $this->hasMany(Event::class, 'tournament_id');
    }
}
