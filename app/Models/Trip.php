<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    public function services(){
        return $this->belongsToMany(Service::class);
    }

    protected $fillable = [
        'photo',
        'trip_name',
        'type',
        'price_per_person',
        'start_date',
        'end_date',
        'days_count',
        'booking_end_date',
        'activities',
        'services',
        'season'
    ];

    protected $casts=[
        'activities'=>'array',
        'services'=>'array',
    ];
}
