<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $casts = [
        'contact' => 'string'
    ];

    protected $fillable = [
        'supplier_name',
        'company_name',
        'address',
        'contact'
    ];

    protected function casts()
    {
        return [
            'supplier_name' => 'string',
            'company_name' => 'string:nullable',
            'address' => 'string:nullable',
            'contact' => 'string', // ?
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
