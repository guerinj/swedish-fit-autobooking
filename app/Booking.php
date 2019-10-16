<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $fillable = [ 'user_id', 'swedishfit_id', 'details', 'will_be_booked_at' ];

    public $casts = [
        'details'           => 'array',
        'will_be_booked_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
