<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPlayerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // public function player()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
   
}
