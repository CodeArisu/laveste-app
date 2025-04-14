<?php

namespace App\Models\Statuses;

use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{   
    protected $table = 'product_status';
    protected $fillable = [
        'status_name'
    ];
}
