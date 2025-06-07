<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlayerTeam extends Model
{
    protected $fillable = [
        'player_id',
        'team_id',
        'status',
        'player_number',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'player_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'team_id');
    }
}
