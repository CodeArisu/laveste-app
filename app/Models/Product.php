<?php

namespace App\Models;

use App\Http\Resources\SubtypeResource;
use App\Http\Resources\TypeResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
    protected $fillable = [
        'product_name',
        'supplier_id',
        'original_price',
        'description'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected function casts()
    {
        return [
            'product_name' => 'string',
            'supplier_id' => 'integer:nullable',
            'original_price' => 'double',
            'description' => 'string'
        ];
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'product_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function garment()
    {
        return $this->hasOne(Garment::class, 'product_id');
    }

    // direct access to subtypes and types
    public function subtypes()
    {
        return $this->hasManyThrough(Subtype::class, ProductType::class, 'product_id', 'id', 'id', 'subtype_id');
    }
    public function types()
    {
        return $this->hasManyThrough(Type::class, ProductType::class, 'product_id', 'id', 'id', 'type_id');
    }
}
