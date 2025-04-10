<?php

namespace App\Models;

use App\Http\Resources\SubtypeResource;
use App\Http\Resources\TypeResource;
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

    // public function getProductTypes()
    // {
    //     $this->productTypes
    //     ->groupBy('type_id')
    //     ->map(function ($group){
    //         return [
    //             'type' => new TypeResource($group->first()->type),
    //             'subtypes' => SubtypeResource::collection($group->pluck('subtype')->unique('id')),
    //         ];
    //     })->values();
    // }

    public function subtypes()
    {
        return $this->hasManyThrough(Subtype::class, ProductType::class, 'product_id', 'id', 'id', 'subtype_id');
    }
}
