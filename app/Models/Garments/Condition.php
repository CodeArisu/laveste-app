<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{   
    use HasFactory;

    protected $table = 'conditions';

    protected $fillable = [
        'id',
        'condition_name'
    ];

    public function garment()
    {
        return $this->hasOne(Garment::class);
    }
}
