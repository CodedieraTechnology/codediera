<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureInstructor
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('instructor.login');
        }

        $user = Auth::user();
        if (!$user || !$user->is_instructor) {
            abort(403);
        }

        return $next($request);
    }
}
