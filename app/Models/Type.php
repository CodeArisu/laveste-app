<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{   
    protected $table = 'types';
    use HasFactory;

    protected $fillable = [
        'type_name',
    ];

    public function productTypes()
    {
        return $this->hasOne(ProductType::class, 'type_id');
    }
}
