<?php

namespace App\Models;

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

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
