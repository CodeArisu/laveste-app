<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisplayProduct extends Model
{   
    use HasFactory;

    protected $fillable = [
        'user_id',
        'garment_id',
        'product_status_id'
    ];
}
