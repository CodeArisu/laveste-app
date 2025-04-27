<?php

namespace App\Models\Statuses;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Model;

class DisplayStatus extends Model
{   
    protected $table = 'product_status';
    protected $fillable = [
        'status_name'
    ];

    public function catalogs()
    {
        return $this->hasOne(Catalog::class, 'product_status_id');
    }
}
