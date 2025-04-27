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
}
