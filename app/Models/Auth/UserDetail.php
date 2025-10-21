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
        'user_id'
    ];

    // UserDetail.php (correct)
    public function user()
    {
        return $this->belongsTo(User::class);
        // user_id is automatically used as foreign key
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
