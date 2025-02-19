<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the admin area.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. This area is restricted to administrators only. If you believe this is an error, please contact system support.');
        }

        return $next($request);
    }
}
