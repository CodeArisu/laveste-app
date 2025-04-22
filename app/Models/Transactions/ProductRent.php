<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class ProductRent extends Model
{   
    protected $table = 'product_rents';
    protected $fillable = [
        'customer_rented_id',
        'rent_details_id',
        'product_rented_status_id',
    ];
}
