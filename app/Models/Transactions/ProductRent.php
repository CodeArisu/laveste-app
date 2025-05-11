<?php

namespace App\Models\Transactions;

use App\Models\Catalog;
use App\Models\Statuses\ProductRentedStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProductRent extends Model
{   
    protected $table = 'product_rents';
    protected $id = 'id';
    
    protected $fillable = [
        'customer_rented_id',
        'rent_details_id',
        'catalog_id',
        'product_rented_status_id',
    ];

    public function customerRent()
    {
        return $this->belongsTo(CustomerRent::class, 'customer_rented_id');
    }

    public function rentDetail()
    {
        return $this->belongsTo(RentDetails::class, 'rent_details');
    }

    public function catalog()
    {   
        return $this->belongsTo(Catalog::class, 'catalog_id');
    }
    
    public function productRentedStatus()
    {
        return $this->belongsTo(ProductRentedStatus::class, 'product_rented_status_id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'id');
    }
}
