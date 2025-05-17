<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $id = 'id';
    
    protected $fillable = [
        'product_rented_id',
        'payment',
        'total_amount',
        'has_discount',
        'discount_amount',
        'vat',
        'payment_method_id'
    ];

    public function productRent()
    {
        return $this->belongsTo(ProductRent::class, 'product_rented_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function formatPayment()
    {
        return '₱' . number_format($this->payment, 2);
    }

    public function getProductName()
    {   
        return $this->productRent->catalog->garment->product->product_name;
    }

    public function getClothingSize()
    {
        return $this->productRent->catalog->garment->size->measurement;
    }

    public function getCustomerName()
    {
        return $this->productRent->customerRent->customerDetail->name;
    }
    
}
