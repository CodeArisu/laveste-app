<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{   
    protected $table = 'user_details';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'contact',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
