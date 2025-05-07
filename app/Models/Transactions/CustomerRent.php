<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class CustomerRent extends Model
{
    protected $table = 'customer_rents';
    
    protected $fillable = [
        'customer_details_id',
        'pickup_date',
        'rented_date',
        'return_date',
    ];

    public function customerDetail()
    {
        return $this->belongsTo(CustomerDetail::class, 'id');
    }


}
