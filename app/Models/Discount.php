<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'coupon_type',
        'description',
        'starting_date',
        'expiry_date',
    ];
}
