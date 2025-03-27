<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garment extends Model
{
    //
    protected $fillable = [
        'product_id',
        'additional_description',
        'poster',
        'size_id',
        'condition_id',
    ];
}
