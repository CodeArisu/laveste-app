<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtype extends Model
{
    use HasFactory;

    protected $table = 'subtypes';
    protected $fillable = [
        'subtype_name'
    ];

    public function productCategories() 
    {
        return $this->hasMany(ProductCategories::class, 'subtype_id');
    }
}
