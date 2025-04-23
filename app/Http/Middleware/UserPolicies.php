<?php

namespace App\Http\Middleware;

use App\Models\Auth\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserPolicies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {   
        $user = auth()->user();
        
        // 1. Check authentication
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Load role relationship if not already loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        // 3. Verify user has a role
        if (!$user->role) {
            abort(403, 'Your account has no role assigned.');
        }

        // 4. Normalize the roles input
        $allowedRoles = $this->normalizeRolesInput($roles);

        // 5. Check authorization
        if ($this->userHasAllowedRole($user, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }

    protected function normalizeRolesInput($roles): array
    {
        // Convert all roles to array if they aren't already
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        // Split comma-separated roles and trim whitespace
        return collect($roles)
            ->flatMap(function ($role) {
                return explode(',', $role);
            })
            ->map(function ($role) {
                return Str::lower(trim($role));
            })
            ->unique()
            ->filter()
            ->toArray();
    }

    protected function userHasAllowedRole($user, array $allowedRoles): bool
    {
        $userRole = Str::lower($user->role->role_name);
        return in_array($userRole, $allowedRoles);
    }
}
