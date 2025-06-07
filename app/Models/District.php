<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Elooquent\Relations\BelongsTo;

class District extends Model
{
    protected $fillable = [
        'name',
        'city_id'
    ];

    public function city() 
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
