<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'customer_rented_id',
        'total_amount',
        'has_discount',
        'discount_amount',
        'vat',
        'payment_method_id'
    ];
}
