<?php

namespace App\Models\Garments;

use App\Models\Catalog;

use App\Models\Garments\Condition;
use App\Models\Garments\Size;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Garment extends Model
{   
    use HasFactory, HasUniqueStringIds;

    protected $table = 'garments';
    protected $fillable = [
        'product_id',
        'rent_price',    
        'additional_description',
        'poster',
        'size_id',
        'condition_id',
    ];

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function catalogs()
    {
        return $this->hasMany(Catalog::class);
    }

     public function getFormattedRentedPrice()
    {
        return '₱' . number_format($this->rent_price, 2);
    }

    public function getImageUrl()
    {
        $path = 'storage/garments/' . $this->poster;
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if ($this->poster == 'no poster' || $this->poster === null) {
            return asset('assets/images/default-dress-image.jpg');
        }
        
        return asset('storage/garments/' . $this->poster);
    }

    protected function isValidUniqueId($value): bool
    {
        return true;
    }

    public function newUniqueId()
    {
        return 'GRM-' . Str::ulid();
    }
}
