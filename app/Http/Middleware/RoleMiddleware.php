<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            Log::info('RoleMiddleware: User not authenticated. Redirecting to login.');
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        Log::info('RoleMiddleware: Authenticated user role is "' . $userRole . '". Expected roles: ' . implode(', ', $roles));

        if (! in_array($userRole, $roles)) {
            Log::warning('RoleMiddleware: Unauthorized access. User role "' . $userRole . '" not in expected roles: ' . implode(', ', $roles));
            abort(403, 'Unauthorized');
        }

        Log::info('RoleMiddleware: User role "' . $userRole . '" authorized for roles: ' . implode(', ', $roles));
        return $next($request);
    }}
