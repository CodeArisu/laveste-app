<?php

namespace App\Models\Transactions;

use App\Models\Statuses\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'customer_details_id',
        'appointment_date',
        'appointment_time',
        'appointment_status_id',
    ];

    protected $casts = [
        'appointment_time' => 'datetime: H:i',
    ];

    public function customerDetail()
    {
        return $this->belongsTo(CustomerDetail::class, 'customer_details_id');
    }

    public function appointmentStatus()
    {
        return $this->belongsTo(AppointmentStatus::class, 'appointment_status_id');
    }
}
