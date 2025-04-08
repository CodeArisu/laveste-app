<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type_id',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function subtype()
    {
        return $this->belongsToMany(Subtype::class);
    }
}
