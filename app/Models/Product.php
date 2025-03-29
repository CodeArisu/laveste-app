<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'supplier_id',
        'original_price',
        'description'
    ];

    public function productType()
    {
        return $this->hasOne(ProductType::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
