<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Ensure role relationship is loaded
        $user->load('role');

        if (!$user->role) {
            abort(403, 'No role assigned to this account.');
        }

        // Convert roles to proper array format
        $allowedRoles = $this->parseRoles($roles);

        if (in_array(Str::lower($user->role->role_name), $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }

    protected function parseRoles($roles): array
    {
        // If $roles is a string, convert to array
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // If $roles is not an array at this point, make it an empty array
        if (!is_array($roles)) {
            $roles = [];
        }

        return collect($roles)
            ->flatMap(function ($role) {
                return explode(',', $role);
            })
            ->map(function ($role) {
                return Str::lower(trim($role));
            })
            ->unique()
            ->filter()
            ->values()
            ->toArray();
    }
}
