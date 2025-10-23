<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRole
{
    public function handle(Request $request, Closure $next, $requiredRole)
    {
        $user = $request->session()->get('user');
        $role = $user['role'] ?? $user['nama_role'] ?? null;

        if (!$role) {
            return redirect('/login')->withErrors(['msg' => 'Silakan login dulu.']);
        }

        $ok = false;
        if ($requiredRole === 'super_admin') {
            $ok = ($role === 'super_admin');
        } elseif ($requiredRole === 'admin') {
            $ok = ($role === 'admin' || $role === 'super_admin');
        } else {
            $ok = ($role === $requiredRole || $role === 'super_admin');
        }

        if (!$ok) {
            return response('Forbidden (role tidak diizinkan).', 403);
        }

        return $next($request);
    }
}
