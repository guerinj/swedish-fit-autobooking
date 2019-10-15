<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $fillable = [ 'user_id', 'swedishfit_id', 'details' ];

    public $casts = [
        'details' => 'array'
    ];
}
