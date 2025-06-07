<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class League extends Model
{
    protected $fillable = ['name'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
