<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{   
    use HasFactory;
    
    protected $table = 'product_categories';

    protected $fillable = [
        'product_id',
        'type_id',
        'subtype_id',
    ];

    protected function casts()
    {
        return [
            'type_id' => 'integer:nullable',
            'subtype_id' => 'integer:nullable',
        ];
    }
    
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
        return $this->belongsTo(Subtype::class, 'subtype_id');
    }
}