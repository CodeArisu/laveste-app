<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    protected $table = 'customer_details';

    protected $fillable = [
        'name',
        'contact',
        'address',
        'email',
    ];

    public function customerRent()
    {
        return $this->hasOne(CustomerRent::class, 'customer_details_id');
    }

    
}
