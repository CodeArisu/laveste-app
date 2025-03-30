<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garment extends Model
{
    //
    protected $fillable = [
        'product_id',
        'rent_price',    
        'additional_description',
        'poster',
        'size_id',
        'condition',
    ];
}
