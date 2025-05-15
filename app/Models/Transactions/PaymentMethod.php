<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = [
        'method_name',
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'id');
    }
}
