<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the student area.');
        }

        if (!auth()->user()->isStudent()) {
            abort(403, 'Access denied. This area is restricted to students only. If you need to submit responses to questionnaires, please use a student account.');
        }

        return $next($request);
    }
}
