<?php

namespace App\Models\Products;

use App\Models\Garments\Garment;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory, HasUniqueStringIds;

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
            'description' => 'string:nullable'
        ];
    }

    public function productCategories() : HasMany
    {
        return $this->hasMany(ProductCategories::class, 'product_id');
    }
    public function supplier() : BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    public function garment() : HasOne
    {
        return $this->hasOne(Garment::class, 'product_id');
    }

    public function getFormattedOriginalPrice()
    {
        return '₱' . number_format($this->original_price, 2);
    }

    public function getFormattedDate()
    {
        $date = new \DateTime($this->created_at);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

    // direct access to subtypes and types
    public function subtypes() : HasManyThrough
    {
        return $this->hasManyThrough(Subtype::class, ProductCategories::class, 'product_id', 'id', 'id', 'subtype_id');
    }
    public function types() : HasOneThrough
    {
        return $this->hasOneThrough(Type::class, ProductCategories::class, 'product_id', 'id', 'id', 'type_id');
    }

    protected function isValidUniqueId($value): bool
    {
        return true;
    }

    public function newUniqueId()
    {
        return 'PRD-' . Str::ulid();
    }

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('product_collection');
        });

        static::updated(function () {
            Cache::forget('product_collection');
        });

        static::deleted(function () {
            Cache::forget('product_collection');
        });
    }
}
