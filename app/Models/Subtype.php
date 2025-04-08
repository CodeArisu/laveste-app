<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtype extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtype_name'
    ];

    public function productType() 
    {
        return $this->belongsToMany(ProductType::class);
    }
}
