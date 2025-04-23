<?php

namespace App\Models\Statuses;

use Illuminate\Database\Eloquent\Model;

class AppointmentStatus extends Model
{
    protected $table = 'appointment_status';
    protected $fillable = [
        'status_name',
    ];
}
