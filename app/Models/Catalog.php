<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\Garments\Garment;
use App\Models\Statuses\DisplayStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Catalog extends Model
{   
    use HasFactory;

    protected $table = 'catalogs';

    protected $fillable = [
        'user_id',
        'garment_id',
        'product_status_id',
    ];

    public function garment()
    {
        return $this->belongsTo(Garment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productStatus()
    {
        return $this->belongsTo(DisplayStatus::class, 'product_status_id');
    }

    public function getFormattedRentPrice()
    {
        return '₱' . number_format($this->garment->rent_price, 2);
    }

    public function getImageUrl()
    {
        if (!$this->garment->poster) {
            return null;
        }
    
        $path = 'storage/garments/' . $this->garment->poster;
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        
        return asset('storage/garments/' . $this->garment->poster);
    }
}
