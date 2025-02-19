<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class LecturerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the lecturer area.');
        }

        if (!auth()->user()->isLecturer()) {
            abort(403, 'Access denied. This area is restricted to lecturers only. If you need to create or manage questionnaires, please use a lecturer account.');
        }

        return $next($request);
    }
}
