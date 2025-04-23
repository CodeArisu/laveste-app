<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class RentDetails extends Model
{
    protected $table = 'rent_details';
    protected $fillable = [
        'venue',
        'event_date',
        'reason_for_renting',
    ];
}
