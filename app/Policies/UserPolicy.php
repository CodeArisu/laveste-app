<?php

namespace App\Policies;

use App\Models\Auth\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
     public function disable(User $user, User $targetUser)
    {
        // Only admins can disable users, and cannot disable themselves
        return $user->role->role_name === 'admin' && $user->id !== $targetUser->id;
    }

    
}
