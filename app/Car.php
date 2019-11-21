<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'year',
        'make',
        'model',
        'trip_count',
        'trip_miles'
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
