<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerInvite extends Model
{
    protected $fillable = [
        'player_id',
        'token',
        'used'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
