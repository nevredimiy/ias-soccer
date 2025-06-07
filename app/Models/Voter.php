<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voter extends Model
{
    protected $fillable = [
        'series_meta_id',
        'voted_player',
        'best_player1',
        'best_player2',
        'worst_player1',
        'worst_player2'
    ];

    // Связь с SeriesMeta
    public function seriesMeta()
    {
        return $this->belongsTo(SeriesMeta::class);
    }

    // Связь с voted_player
    public function votedPlayer()
    {
        return $this->belongsTo(Player::class, 'voted_player');
    }

    // Связь с лучшими игроками
    public function bestPlayer1()
    {
        return $this->belongsTo(Player::class, 'best_player1');
    }

    public function bestPlayer2()
    {
        return $this->belongsTo(Player::class, 'best_player2');
    }

    // Связь с худшими игроками
    public function worstPlayer1()
    {
        return $this->belongsTo(Player::class, 'worst_player1');
    }

    public function worstPlayer2()
    {
        return $this->belongsTo(Player::class, 'worst_player2');
    }
}
