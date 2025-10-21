<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;

class CustomerRent extends Model
{
    protected $table = 'customer_rents';
    
    protected $fillable = [
        'customer_details_id',
        'pickup_date',
        'rented_date',
        'return_date',
    ];

    public function customerDetail()
    {
        return $this->belongsTo(CustomerDetail::class, 'customer_details_id');
    }

    public function productRents()
    {
        return $this->hasMany(ProductRent::class, 'customer_rented_id');
    }

    public function convertDateFormat()
    {
        $date = new \DateTime($this->pickup_date);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

     public function convertStartDateFormat()
    {
        $date = new \DateTime($this->start_date);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

     public function convertEndDateFormat()
    {
        $date = new \DateTime($this->return_date);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

}
