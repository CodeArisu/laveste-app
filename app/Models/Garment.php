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
        'condition_id',
    ];

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
