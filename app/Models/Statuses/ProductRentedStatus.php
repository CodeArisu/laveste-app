<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class ProductRentedStatus extends Model
{
    protected $table = 'product_rented_status';
    protected $fillable = [
        'status_name',
    ];
    public $timestamps = true;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
