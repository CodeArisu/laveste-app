<?php

namespace App\Models\Transactions;

use App\Models\Statuses\ProductRentedStatus;
use Illuminate\Database\Eloquent\Model;

class ProductRent extends Model
{   
    protected $table = 'product_rents';
    
    protected $fillable = [
        'customer_rented_id',
        'rent_details_id',
        'catalog_product_id',
        'product_rented_status_id',
    ];

    public function customerRent()
    {
        return $this->belongsTo(CustomerRent::class, 'customer_rented_id');
    }
    
    public function productRentedStatus()
    {
        return $this->belongsTo(ProductRentedStatus::class, 'product_rented_status_id');
    }
}
