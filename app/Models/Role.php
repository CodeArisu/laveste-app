<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    use HasFactory;

    /**
    * mass assignable attributes
    * 
    * @var list<any>
    */
    protected $fillable = [
        'role_name',
    ];

    // one to one 
    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }
}
