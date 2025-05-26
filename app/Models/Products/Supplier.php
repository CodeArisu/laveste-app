<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use HasFactory, HasUniqueStringIds;

    protected $table = 'suppliers';

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

     protected function isValidUniqueId($value): bool
    {
        return true;
    }

    public function newUniqueId()
    {
        return 'SUP-' . Str::ulid();
    }
}
