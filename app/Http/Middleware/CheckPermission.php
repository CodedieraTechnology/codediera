<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        if (!$user || !$user->is_admin || !$user->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
