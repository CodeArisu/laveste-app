<?php

namespace App\Models\Garments;

use App\Models\Products\Product;
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
        return $this->belongsTo(Condition::class, 'condition_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
