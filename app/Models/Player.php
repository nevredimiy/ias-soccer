<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'last_name',
        'first_name',
        'phone',
        'tg',
        'birth_date',
        'photo',
        'rating',
        'verify_rating',

    ];

    // Добавляем связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setPhotoAttribute($value)
    {
        $this->attributes['photo'] = $value ?: '/img/avatars/default_avatar.jpg';
    }

    public function playerTeams()
    {
        return $this->hasMany(PlayerTeam::class);
    }

    public function playerInvites()
    {
        return $this->hasMany(PlayerInvite::class, 'player_id');
    }

    public function playerInvite()
    {
        return $this->hasOne(PlayerInvite::class, 'player_id');
    }

    public function seriesPlayer()
    {
        return $this->hasMany(SeriesPlayer::class);
    }

    // public function getPlayerNumber($teamId)
    // {
    //     $playerTeam = $this->playerTeams()->where('team_id', $teamId)->first();
    //     return $playerTeam ? $playerTeam->player_number : null;
    // }


    public function getPlayerNumber($seriesId, $teamId)
    {
        $seriesPlayer = $this->seriesPlayer()
            ->where('team_id', $teamId)
            ->where('series_meta_id', $seriesId)
            ->first();
        return $seriesPlayer ? $seriesPlayer->player_number : null;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'player_teams')
        ->withPivot('status')
        ->withPivot('player_number')
            ->withTimestamps();
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    protected $casts = [
        'birth_date' => 'date',
    ];

    

}
