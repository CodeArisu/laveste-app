<?php

namespace App\Models\Statuses;

use App\Models\Transactions\ProductRent;
use Illuminate\Database\Eloquent\Model;

class ProductRentedStatus extends Model
{
    protected $table = 'product_rented_status';

    protected $fillable = [
        'status_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function productRent()
    {
        return $this->hasOne(ProductRent::class, 'product_rented_status_id');
    }
}
