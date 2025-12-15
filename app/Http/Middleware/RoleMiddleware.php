<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check if user has the required role
        if (!in_array($user->role, $roles)) {
            // Redirect based on user's actual role
            return $this->redirectBasedOnRole($user->role);
        }

        return $next($request);
    }
    private function redirectBasedOnRole($role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard')->with('error', 'Access denied.'),
            'vendor' => redirect()->route('vendor.dashboard')->with('error', 'Access denied.'),
            'customer' => redirect()->route('customer.dashboard')->with('error', 'Access denied.'),
            default => redirect()->route('login')->with('error', 'Invalid role.'),
        };
    }
}
