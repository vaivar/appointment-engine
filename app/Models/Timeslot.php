<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    protected $table = 'timetables';

    protected $fillable = [
        'slots_total',
        'slots_occupied',
        'date',
        'time',
        'salon_id',
        'admin_id',
    ];

    protected $attributes = [
        'slots_occupied' => 0,
    ];

    public function lastTouched()
    {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }

    public function salon()
    {
        return $this->belongsTo('App\Models\Salon', 'salon_id');
    }
}
