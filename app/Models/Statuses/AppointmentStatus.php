<?php

namespace App\Models\Statuses;

use App\Models\Transactions\Appointment;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatus extends Model
{
    protected $table = 'appointment_status';
    
    protected $fillable = [
        'status_name',
    ];

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id');
    }
}
