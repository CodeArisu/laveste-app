<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    protected $fillable = [
        'measurement',
        'length',
        'width',
    ];

    public function garments()
    {
        return $this->hasOne(Garment::class);
    }
}
