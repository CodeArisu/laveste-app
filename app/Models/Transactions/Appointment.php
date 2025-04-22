<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = [
        'customer_details_id',
        'appointment_date',
        'appointment_status_id',
    ];
}
