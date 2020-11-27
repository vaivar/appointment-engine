<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'message',
        'salon_id',
        'timetable_id'
    ];
}
