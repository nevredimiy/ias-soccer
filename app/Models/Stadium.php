<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stadium extends Model
{
    protected $fillable = [
        'name',
        'address',
        'location_id',
        'photo',
        'phone',
        'fields_40x20',
        'fields_60x40',
        'parking_spots',
        'has_shower',
        'has_speaker_system',
        'has_wardrobe',
        'has_toilet'
    ];

    protected $table = 'stadiums';


    public function setPhotoAttribute($value)
    {
        $this->attributes['photo'] = $value ?: '/img/stadium/stadium_placeholder.png';
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
