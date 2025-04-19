<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{   
    use HasFactory;

    protected $table = 'types';
    
    protected $fillable = [
        'type_name',
    ];

    public function productCategories()
    {
        return $this->hasOne(ProductCategories::class, 'type_id');
    }
}
