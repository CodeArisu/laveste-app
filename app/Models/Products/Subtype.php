<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subtype extends Model
{
    use HasFactory, HasUniqueStringIds;

    protected $table = 'subtypes';

    protected $fillable = [
        'subtype_name'
    ];

    public function productCategories()
    {
        return $this->hasMany(ProductCategories::class, 'subtype_id');
    }

    protected function isValidUniqueId($value): bool
    {
        return true;
    }

    public function newUniqueId()
    {
        return Str::ulid();
    }
}
