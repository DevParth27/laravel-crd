<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && !$user->isAdmin()) {
            return redirect()->route('excel.index')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
